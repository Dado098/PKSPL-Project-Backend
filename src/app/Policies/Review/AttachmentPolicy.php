<?php

namespace App\Policies\Review;

use App\Models\Role;
use App\Models\User;
use App\Models\Review\CommentAttachment;

class AttachmentPolicy
{
    public function create(User $user): bool
    {
        return in_array($user->role->nama_role, [Role::ANALYST, Role::PENELITI], true);
    }

    public function delete(User $user, CommentAttachment $attachment): bool
    {
        return $attachment->comment->id_user === $user->id_user || $user->role->nama_role === Role::ADMIN;
    }
}
