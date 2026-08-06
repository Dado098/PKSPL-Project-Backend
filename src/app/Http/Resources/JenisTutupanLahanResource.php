<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JenisTutupanLahanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_jenis_tutupan_lahan' => $this->id_jenis_tutupan_lahan,
            'id_index' => $this->id_index,
            'nama_tutupan_lahan' => $this->nama_tutupan_lahan,
            'kategori' => $this->kategori,
            'luas' => $this->luas,
            'satuan_luas' => $this->satuan_luas,
            'geometry' => $this->geometry,
            'deskripsi' => $this->deskripsi,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
