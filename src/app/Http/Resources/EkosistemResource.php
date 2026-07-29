<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EkosistemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return ['id_ekosistem' => $this->id_ekosistem, 'nama_ekosistem' => $this->nama_ekosistem, 'deskripsi' => $this->deskripsi, 'status' => $this->status, 'created_at' => $this->created_at, 'updated_at' => $this->updated_at];
    }
}
