<?php

namespace App\Policies\Review;

use App\Models\Role;
use App\Models\User;
use App\Models\Review\Review;

class ReviewPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Review $review): bool
    {
        return $user->role->nama_role === Role::ANALYST || $review->proyek->id_user === $user->id_user;
    }

    public function create(User $user): bool
    {
        return in_array($user->role->nama_role, [Role::ANALYST, Role::ADMIN], true);
    }

    public function update(User $user, Review $review): bool
    {
        if ($review->status === 'Closed') {
            return false;
        }
        return $review->id_reviewer === $user->id_user || $user->role->nama_role === Role::ADMIN;
    }

    public function resolve(User $user, Review $review): bool
    {
        if ($review->status !== 'Open') {
            return false;
        }
        return in_array($user->role->nama_role, [Role::ANALYST, Role::ADMIN], true);
    }

    public function reopen(User $user, Review $review): bool
    {
        if ($review->status !== 'Resolved') {
            return false;
        }
        return in_array($user->role->nama_role, [Role::ANALYST, Role::ADMIN], true);
    }

    public function close(User $user, Review $review): bool
    {
        if ($review->status === 'Closed') {
            return false;
        }
        return in_array($user->role->nama_role, [Role::ANALYST, Role::ADMIN], true);
    }
}
