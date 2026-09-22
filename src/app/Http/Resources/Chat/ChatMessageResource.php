<?php

declare(strict_types=1);

namespace App\Http\Resources\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $currentUser = $request->user();
        
        // Calculate whether message is read
        $isRead = false;
        if ($currentUser && (int) $this->id_sender === (int) $currentUser->id_user) {
            // For sender: check if other participant has read past this message
            $otherParticipant = $this->conversation?->participants
                ?->first(fn ($p) => (int) $p->id_user !== (int) $currentUser->id_user);
            if ($otherParticipant && $otherParticipant->last_read_at && $this->created_at) {
                $isRead = $otherParticipant->last_read_at >= $this->created_at;
            }
        } else {
            // Received message is already read if viewer's last_read_at >= message created_at
            $myParticipant = $this->conversation?->participants
                ?->first(fn ($p) => (int) $p->id_user === (int) $currentUser?->id_user);
            if ($myParticipant && $myParticipant->last_read_at && $this->created_at) {
                $isRead = $myParticipant->last_read_at >= $this->created_at;
            }
        }

        $senderRole = $this->sender?->role?->nama_role ?? 'User';
        $senderName = $this->sender?->nama ?? 'Pengguna';

        return [
            'id' => (string) $this->id_message,
            'id_message' => $this->id_message,
            'conversationId' => (string) $this->id_conversation,
            'conversation_id' => $this->id_conversation,
            'senderId' => (string) $this->id_sender,
            'sender_id' => $this->id_sender,
            'senderRole' => $senderRole,
            'sender_role' => $senderRole,
            'senderName' => $senderName,
            'sender_name' => $senderName,
            'text' => $this->message,
            'message' => $this->message,
            'messageType' => $this->message_type,
            'message_type' => $this->message_type,
            'timestamp' => $this->created_at ? $this->created_at->format('H:i') : '',
            'createdAt' => $this->created_at ? $this->created_at->toIso8601String() : '',
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : '',
            'isRead' => $isRead,
            'is_read' => $isRead,
            'projectContext' => $this->proyek ? [
                'projectCode' => $this->proyek->kode_proyek ?: ('PRJ-' . str_pad((string) $this->proyek->id_proyek, 3, '0', STR_PAD_LEFT)),
                'projectName' => $this->proyek->nama_proyek,
            ] : null,
            'attachments' => MessageAttachmentResource::collection($this->whenLoaded('attachments')),
        ];
    }
}