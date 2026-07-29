<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProyekResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return ['id_proyek' => $this->id_proyek, 'id_user' => $this->id_user, 'nama_proyek' => $this->nama_proyek, 'tujuan_valuasi' => $this->tujuan_valuasi, 'lokasi' => $this->lokasi, 'tahun' => $this->tahun, 'deskripsi' => $this->deskripsi, 'status' => $this->status, 'created_at' => $this->created_at, 'updated_at' => $this->updated_at];
    }
}
