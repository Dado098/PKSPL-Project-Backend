<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\RoleResource;

/** Membentuk response API untuk data pengguna. */
class UserResource extends JsonResource
{
    /** Mengubah model pengguna menjadi payload response tanpa password. */
    public function toArray(Request $request): array
    {
        return [
            'id_user' => $this->id_user,
            'id_role' => $this->id_role,
            'nama' => $this->nama,
            'email' => $this->email,
            'foto' => $this->foto,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'role' => $this->relationLoaded('role') ? new RoleResource($this->role) : null,
        ];
    }
}
