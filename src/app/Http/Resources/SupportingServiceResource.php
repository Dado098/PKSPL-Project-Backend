<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** Membentuk response API untuk jasa pendukung. */
class SupportingServiceResource extends JsonResource
{
    /** Mengubah model jasa pendukung menjadi payload response. */
    public function toArray(Request $request): array
    {
        return ['id_supporting' => $this->id_supporting, 'id_area' => $this->id_area, 'fungsi_pendukung' => $this->fungsi_pendukung, 'deskripsi' => $this->deskripsi, 'referensi' => $this->referensi, 'nilai' => $this->nilai, 'created_at' => $this->created_at, 'updated_at' => $this->updated_at];
    }
}
