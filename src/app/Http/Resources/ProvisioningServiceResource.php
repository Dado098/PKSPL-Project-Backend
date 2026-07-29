<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProvisioningServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return ['id_provisioning' => $this->id_provisioning, 'id_area' => $this->id_area, 'nama_objek' => $this->nama_objek, 'produktivitas' => $this->produktivitas, 'harga_pasar' => $this->harga_pasar, 'luas_pemanfaatan' => $this->luas_pemanfaatan, 'satuan_luas' => $this->satuan_luas, 'referensi' => $this->referensi, 'nilai' => $this->nilai, 'created_at' => $this->created_at, 'updated_at' => $this->updated_at];
    }
}
