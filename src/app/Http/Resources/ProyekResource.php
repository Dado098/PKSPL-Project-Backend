<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Bentuk response proyek beserta struktur wilayahnya.
 */
class ProyekResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_proyek' => $this->id_proyek,
            'id_user' => $this->id_user,
            'nama_proyek' => $this->nama_proyek,
            'tujuan_valuasi' => $this->tujuan_valuasi,
            'provinsi' => new ProvinsiResource($this->whenLoaded('provinsi')),
            'kabupaten_kota' => new KabupatenKotaResource($this->whenLoaded('kabupatenKota')),
            'kecamatan' => new KecamatanResource($this->whenLoaded('kecamatan')),
            'desa_kelurahan' => new DesaKelurahanResource($this->whenLoaded('desaKelurahan')),
            'alamat_lengkap' => $this->alamat_lengkap,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'luas' => $this->luas,
            'satuan_luas' => $this->satuan_luas,
            'geometry' => $this->geometry,
            'shapefile_files' => $this->shapefile_files,
            'tahun' => $this->tahun,
            'deskripsi' => $this->deskripsi,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
