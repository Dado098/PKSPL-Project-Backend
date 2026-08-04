<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** Membentuk response API untuk data jasa budaya. */
class CulturalServiceResource extends JsonResource
{
    /** Mengubah model jasa budaya menjadi payload response. */
    public function toArray(Request $request): array
    {
        return ['id_cultural' => $this->id_cultural, 'id_area' => $this->id_area, 'nama_aktivitas' => $this->nama_aktivitas, 'jumlah_pengunjung' => $this->jumlah_pengunjung, 'biaya_perjalanan' => $this->biaya_perjalanan, 'frekuensi' => $this->frekuensi, 'referensi' => $this->referensi, 'nilai' => $this->nilai, 'kategori_tev' => $this->kategori_tev, 'id_provinsi' => $this->id_provinsi, 'id_kabupaten_kota' => $this->id_kabupaten_kota, 'id_kecamatan' => $this->id_kecamatan, 'id_desa_kelurahan' => $this->id_desa_kelurahan, 'wilayah' => $this->when($this->relationLoaded('provinsi') || $this->relationLoaded('kabupatenKota') || $this->relationLoaded('kecamatan') || $this->relationLoaded('desaKelurahan'), [
            'provinsi' => $this->whenLoaded('provinsi', fn () => new ProvinsiResource($this->provinsi)),
            'kabupaten_kota' => $this->whenLoaded('kabupatenKota', fn () => new KabupatenKotaResource($this->kabupatenKota)),
            'kecamatan' => $this->whenLoaded('kecamatan', fn () => new KecamatanResource($this->kecamatan)),
            'desa_kelurahan' => $this->whenLoaded('desaKelurahan', fn () => new DesaKelurahanResource($this->desaKelurahan)),
        ]), 'created_at' => $this->created_at, 'updated_at' => $this->updated_at];
    }
}
