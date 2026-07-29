<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ValidasiAnalystResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return ['id_validasi' => $this->id_validasi, 'id_hasil' => $this->id_hasil, 'id_user' => $this->id_user, 'status_validasi' => $this->status_validasi, 'metode_analisis' => $this->metode_analisis, 'catatan' => $this->catatan, 'tanggal_validasi' => $this->tanggal_validasi, 'created_at' => $this->created_at, 'updated_at' => $this->updated_at];
    }
}
