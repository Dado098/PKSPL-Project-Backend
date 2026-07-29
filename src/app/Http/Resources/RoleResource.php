<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return ['id_role' => $this->id_role, 'nama_role' => $this->nama_role, 'deskripsi' => $this->deskripsi, 'created_at' => $this->created_at, 'updated_at' => $this->updated_at];
    }
}
