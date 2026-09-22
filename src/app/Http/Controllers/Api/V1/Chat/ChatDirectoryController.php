<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Chat;

use App\Http\Controllers\Controller;
use App\Http\Resources\Chat\ChatUserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChatDirectoryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $currentUser = $request->user();
        $userRole = $currentUser->role ? Role::normalize($currentUser->role->nama_role) : '';
        $search = $request->input('search');

        $query = User::query()
            ->where('id_user', '!=', $currentUser->id_user)
            ->where('status', 'Aktif')
            ->with(['role', 'proyek']);

        // Role-based visibility:
        // Analyst: can see Peneliti and Admin
        // Peneliti: can see Analyst and Admin
        // Admin: can see all
        if ($userRole === Role::ANALYST) {
            $query->whereHas('role', fn ($q) => $q->whereIn('nama_role', [Role::PENELITI, Role::ADMIN, 'Administrator']));
        } elseif ($userRole === Role::PENELITI) {
            $query->whereHas('role', fn ($q) => $q->whereIn('nama_role', [Role::ANALYST, Role::ADMIN, 'Administrator']));
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        $users = $query->orderBy('nama', 'asc')->get();

        return ChatUserResource::collection($users);
    }
}