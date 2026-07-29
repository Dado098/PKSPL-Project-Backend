<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoriResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return ['id_histori' => $this->id_histori, 'id_user' => $this->id_user, 'id_proyek' => $this->id_proyek, 'aktivitas' => $this->aktivitas, 'keterangan' => $this->keterangan, 'created_at' => $this->created_at];
    }
}
