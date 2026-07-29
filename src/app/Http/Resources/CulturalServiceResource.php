<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CulturalServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return ['id_cultural' => $this->id_cultural, 'id_area' => $this->id_area, 'nama_aktivitas' => $this->nama_aktivitas, 'jumlah_pengunjung' => $this->jumlah_pengunjung, 'biaya_perjalanan' => $this->biaya_perjalanan, 'frekuensi' => $this->frekuensi, 'referensi' => $this->referensi, 'nilai' => $this->nilai, 'created_at' => $this->created_at, 'updated_at' => $this->updated_at];
    }
}
