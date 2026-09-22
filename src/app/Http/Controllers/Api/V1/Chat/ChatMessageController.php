<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Chat;

use App\Http\Controllers\Controller;
use App\Http\Resources\Chat\ChatMessageResource;
use App\Models\ChatMessage;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\MessageAttachment;
use App\Events\ChatMessageSent;
use App\Notifications\Chat\NewChatMessageNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChatMessageController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, Conversation $conversation): AnonymousResourceCollection
    {
        $this->authorize('view', $conversation);

        $perPage = (int) $request->input('per_page', 50);
        if ($perPage < 1 || $perPage > 100) {
            $perPage = 50;
        }

        $messages = $conversation->messages()
            ->with(['sender.role', 'proyek', 'attachments', 'conversation.participants'])
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);

        return ChatMessageResource::collection($messages);
    }

    public function store(Request $request, Conversation $conversation): JsonResponse
    {
        $this->authorize('sendMessage', $conversation);

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:5000'],
            'text' => ['nullable', 'string', 'max:5000'], // alias for message
            'id_proyek' => ['nullable', 'integer', 'exists:proyek,id_proyek'],
            'reply_to_id' => ['nullable', 'integer', 'exists:messages,id_message'],
            'file' => ['nullable', 'file', 'max:20480', 'mimes:pdf,doc,docx,xls,xlsx,csv,png,jpg,jpeg,webp,zip,geojson,json,shp'],
        ]);

        $text = $validated['message'] ?? $validated['text'] ?? '';
        $hasFile = $request->hasFile('file');

        if (trim($text) === '' && !$hasFile) {
            return response()->json(['message' => 'Pesan atau lampiran berkas wajib diisi.'], 422);
        }

        $currentUser = $request->user();

        $messageType = $hasFile ? 'attachment' : 'text';

        $chatMessage = ChatMessage::create([
            'id_conversation' => $conversation->id_conversation,
            'id_sender' => $currentUser->id_user,
            'id_proyek' => $validated['id_proyek'] ?? $conversation->id_proyek,
            'message' => $text,
            'message_type' => $messageType,
            'reply_to_id' => $validated['reply_to_id'] ?? null,
        ]);

        if ($hasFile) {
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $mimeType = $file->getClientMimeType() ?: 'application/octet-stream';

            // Determine friendly category
            $category = match ($extension) {
                'pdf' => 'pdf',
                'doc', 'docx' => 'doc',
                'xls', 'xlsx', 'csv' => 'sheet',
                'jpg', 'jpeg', 'png', 'webp' => 'image',
                'geojson', 'shp', 'zip' => 'spatial',
                default => 'doc',
            };

            $storagePath = 'chat-attachments/' . date('Y/m') . '/' . Str::uuid() . '.' . $extension;
            Storage::disk('public')->putFileAs('', $file, $storagePath);

            MessageAttachment::create([
                'id_message' => $chatMessage->id_message,
                'file_name' => $fileName,
                'file_path' => $storagePath,
                'file_size' => $fileSize,
                'mime_type' => $mimeType,
                'file_type' => $category,
            ]);
        }

        // Touch conversation to update timestamp
        $conversation->touch();

        // Update sender's last_read_at
        ConversationParticipant::query()
            ->where('id_conversation', $conversation->id_conversation)
            ->where('id_user', $currentUser->id_user)
            ->update(['last_read_at' => now()]);

        $chatMessage->load(['sender.role', 'proyek', 'attachments', 'conversation.participants']);

        // 1. Dispatch Realtime Event
        try {
            broadcast(new ChatMessageSent($chatMessage));
        } catch (\Throwable $e) {
            Log::warning('Chat realtime broadcast notice: ' . $e->getMessage());
        }

        // 2. Dispatch In-App & Queued Email Notifications to other participants
        $conversation->loadMissing('participants.user.notificationPreference');
        foreach ($conversation->participants as $participant) {
            if ((int) $participant->id_user !== (int) $currentUser->id_user && $participant->user) {
                try {
                    $participant->user->notify(new NewChatMessageNotification(
                        $chatMessage,
                        $conversation,
                        $currentUser
                    ));
                } catch (\Throwable $e) {
                    Log::warning('Chat notification dispatch notice: ' . $e->getMessage());
                }
            }
        }

        return (new ChatMessageResource($chatMessage))->response()->setStatusCode(201);
    }
}