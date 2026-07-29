<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MetodeValuasiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return ['id_metode' => $this->id_metode, 'nama_metode' => $this->nama_metode, 'deskripsi' => $this->deskripsi, 'formula' => $this->formula, 'parameter' => $this->parameter, 'status' => $this->status, 'created_at' => $this->created_at, 'updated_at' => $this->updated_at];
    }
}
