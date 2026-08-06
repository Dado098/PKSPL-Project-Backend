<?php

namespace App\Policies\Review;

use App\Models\Role;
use App\Models\User;
use App\Models\Review\ReviewComment;

class ReviewCommentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ReviewComment $comment): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role->nama_role, [Role::ANALYST, Role::PENELITI], true);
    }

    public function update(User $user, ReviewComment $comment): bool
    {
        if ($comment->deleted_at !== null) {
            return false;
        }
        return $comment->id_user === $user->id_user;
    }

    public function delete(User $user, ReviewComment $comment): bool
    {
        if ($comment->deleted_at !== null) {
            return false;
        }
        return $comment->id_user === $user->id_user || $user->role->nama_role === Role::ADMIN;
    }

    public function reply(User $user, ReviewComment $comment): bool
    {
        if ($comment->deleted_at !== null) {
            return false;
        }
        return in_array($user->role->nama_role, [Role::ANALYST, Role::PENELITI], true);
    }
}
