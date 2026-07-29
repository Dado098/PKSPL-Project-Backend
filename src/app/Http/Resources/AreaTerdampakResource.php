<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AreaTerdampakResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return ['id_area' => $this->id_area, 'id_proyek' => $this->id_proyek, 'id_ekosistem' => $this->id_ekosistem, 'nama_area' => $this->nama_area, 'latitude' => $this->latitude, 'longitude' => $this->longitude, 'luas' => $this->luas, 'satuan_luas' => $this->satuan_luas, 'deskripsi' => $this->deskripsi, 'created_at' => $this->created_at, 'updated_at' => $this->updated_at];
    }
}
