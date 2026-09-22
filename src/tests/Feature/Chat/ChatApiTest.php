<?php

declare(strict_types=1);

namespace Tests\Feature\Chat;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\ChatMessage;
use App\Models\Proyek;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ChatApiTest extends TestCase
{
    use DatabaseTransactions;

    protected User $analyst;
    protected User $peneliti;
    protected User $thirdParty;

    protected function setUp(): void
    {
        parent::setUp();

        $roleAnalyst = Role::firstOrCreate(['nama_role' => 'Analyst'], ['deskripsi' => 'Analyst']);
        $rolePeneliti = Role::firstOrCreate(['nama_role' => 'Peneliti'], ['deskripsi' => 'Peneliti']);
        $roleGuest = Role::firstOrCreate(['nama_role' => 'Guest'], ['deskripsi' => 'Guest']);

        $this->analyst = User::factory()->create(['id_role' => $roleAnalyst->id_role]);
        $this->peneliti = User::factory()->create(['id_role' => $rolePeneliti->id_role]);
        $this->thirdParty = User::factory()->create(['id_role' => $roleGuest->id_role]);
    }

    public function test_analyst_can_access_chat_directory(): void
    {
        $response = $this->actingAs($this->analyst)->getJson('/api/v1/chat/directory');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'email', 'role', 'isOnline', 'lastSeen']
            ]
        ]);
    }

    public function test_analyst_can_create_conversation_with_peneliti(): void
    {
        $response = $this->actingAs($this->analyst)->postJson('/api/v1/conversations', [
            'recipient_id' => $this->peneliti->id_user,
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('data.type', 'direct');
        $this->assertDatabaseHas('conversations', [
            'created_by' => $this->analyst->id_user,
        ]);
    }

    public function test_analyst_can_send_message_in_conversation(): void
    {
        Notification::fake();

        // 1. Create conversation
        $convResponse = $this->actingAs($this->analyst)->postJson('/api/v1/conversations', [
            'recipient_id' => $this->peneliti->id_user,
        ]);
        $convId = $convResponse->json('data.id');

        // 2. Send message
        $response = $this->actingAs($this->analyst)->postJson("/api/v1/conversations/{$convId}/messages", [
            'message' => 'Halo Peneliti, mohon klarifikasi data mangrove.',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('data.text', 'Halo Peneliti, mohon klarifikasi data mangrove.');
        $this->assertDatabaseHas('messages', [
            'id_conversation' => $convId,
            'id_sender' => $this->analyst->id_user,
            'message' => 'Halo Peneliti, mohon klarifikasi data mangrove.',
        ]);

        Notification::assertSentTo($this->peneliti, \App\Notifications\Chat\NewChatMessageNotification::class);
    }

    public function test_analyst_can_send_message_with_attachment(): void
    {
        Storage::fake('public');

        $convResponse = $this->actingAs($this->analyst)->postJson('/api/v1/conversations', [
            'recipient_id' => $this->peneliti->id_user,
        ]);
        $convId = $convResponse->json('data.id');

        $fakePdf = UploadedFile::fake()->create('Catatan_Telaah.pdf', 1500, 'application/pdf');

        $response = $this->actingAs($this->analyst)->postJson("/api/v1/conversations/{$convId}/messages", [
            'message' => 'Berikut berkas telaah:',
            'file' => $fakePdf,
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('data.attachments.0.fileName', 'Catatan_Telaah.pdf');
        $response->assertJsonPath('data.attachments.0.fileType', 'pdf');
        $this->assertDatabaseHas('message_attachments', [
            'file_name' => 'Catatan_Telaah.pdf',
            'file_type' => 'pdf',
        ]);
    }

    public function test_peneliti_can_read_messages(): void
    {
        $convResponse = $this->actingAs($this->analyst)->postJson('/api/v1/conversations', [
            'recipient_id' => $this->peneliti->id_user,
        ]);
        $convId = $convResponse->json('data.id');

        $this->actingAs($this->analyst)->postJson("/api/v1/conversations/{$convId}/messages", [
            'message' => 'Pesan dari analyst',
        ]);

        $response = $this->actingAs($this->peneliti)->getJson("/api/v1/conversations/{$convId}/messages");

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }

    public function test_unauthorized_user_cannot_access_conversation_anti_idor(): void
    {
        $convResponse = $this->actingAs($this->analyst)->postJson('/api/v1/conversations', [
            'recipient_id' => $this->peneliti->id_user,
        ]);
        $convId = $convResponse->json('data.id');

        // Third party attempts to read conversation
        $response = $this->actingAs($this->thirdParty)->getJson("/api/v1/conversations/{$convId}");
        $response->assertStatus(403);

        // Third party attempts to read messages
        $msgResponse = $this->actingAs($this->thirdParty)->getJson("/api/v1/conversations/{$convId}/messages");
        $msgResponse->assertStatus(403);

        // Third party attempts to send message
        $sendResponse = $this->actingAs($this->thirdParty)->postJson("/api/v1/conversations/{$convId}/messages", [
            'message' => 'Pesan ilegal',
        ]);
        $sendResponse->assertStatus(403);
    }

    public function test_user_can_mark_conversation_as_read(): void
    {
        $convResponse = $this->actingAs($this->analyst)->postJson('/api/v1/conversations', [
            'recipient_id' => $this->peneliti->id_user,
        ]);
        $convId = $convResponse->json('data.id');

        $this->actingAs($this->analyst)->postJson("/api/v1/conversations/{$convId}/messages", [
            'message' => 'Pesan belum dibaca',
        ]);

        $readResponse = $this->actingAs($this->peneliti)->postJson("/api/v1/conversations/{$convId}/read");
        $readResponse->assertStatus(200);

        $participant = ConversationParticipant::where('id_conversation', $convId)
            ->where('id_user', $this->peneliti->id_user)
            ->first();

        $this->assertNotNull($participant->last_read_at);
    }

    public function test_user_can_manage_notification_preferences(): void
    {
        $getResponse = $this->actingAs($this->analyst)->getJson('/api/v1/notification-preferences');
        $getResponse->assertStatus(200);

        $updateResponse = $this->actingAs($this->analyst)->putJson('/api/v1/notification-preferences', [
            'email_chat' => false,
            'email_revision' => true,
        ]);

        $updateResponse->assertStatus(200);
        $updateResponse->assertJsonPath('preferences.email_chat', false);
    }
}