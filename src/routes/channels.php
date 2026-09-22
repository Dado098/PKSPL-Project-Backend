<?php

use App\Models\ConversationParticipant;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['auth:sanctum'], 'prefix' => 'api/v1']);


Broadcast::channel('conversation.{id}', function (User $user, int|string $id): bool {
    return ConversationParticipant::where('id_conversation', (int) $id)
        ->where('id_user', (int) $user->id_user)
        ->exists();
});

Broadcast::channel('user.{id}', function (User $user, int|string $id): bool {
    return (int) $user->id_user === (int) $id;
});