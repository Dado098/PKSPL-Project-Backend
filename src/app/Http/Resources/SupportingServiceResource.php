<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupportingServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return ['id_supporting' => $this->id_supporting, 'id_area' => $this->id_area, 'fungsi_pendukung' => $this->fungsi_pendukung, 'deskripsi' => $this->deskripsi, 'referensi' => $this->referensi, 'nilai' => $this->nilai, 'created_at' => $this->created_at, 'updated_at' => $this->updated_at];
    }
}
