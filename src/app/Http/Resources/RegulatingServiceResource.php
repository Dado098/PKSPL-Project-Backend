<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegulatingServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return ['id_regulating' => $this->id_regulating, 'id_area' => $this->id_area, 'jenis_regulating' => $this->jenis_regulating, 'indikator' => $this->indikator, 'satuan' => $this->satuan, 'nilai_indikator' => $this->nilai_indikator, 'referensi' => $this->referensi, 'nilai' => $this->nilai, 'created_at' => $this->created_at, 'updated_at' => $this->updated_at];
    }
}
