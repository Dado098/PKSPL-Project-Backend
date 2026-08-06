<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** Membentuk response API untuk jasa pengaturan. */
class RegulatingServiceResource extends JsonResource
{
    /** Mengubah model jasa pengaturan menjadi payload response. */
    public function toArray(Request $request): array
    {
        return ['id_regulating' => $this->id_regulating, 'id_jenis_tutupan_lahan' => $this->id_jenis_tutupan_lahan, 'jenis_regulating' => $this->jenis_regulating, 'indikator' => $this->indikator, 'satuan' => $this->satuan, 'nilai_indikator' => $this->nilai_indikator, 'referensi' => $this->referensi, 'nilai' => $this->nilai, 'kategori_tev' => $this->kategori_tev, 'id_provinsi' => $this->id_provinsi, 'id_kabupaten_kota' => $this->id_kabupaten_kota, 'id_kecamatan' => $this->id_kecamatan, 'id_desa_kelurahan' => $this->id_desa_kelurahan, 'wilayah' => $this->when($this->relationLoaded('provinsi') || $this->relationLoaded('kabupatenKota') || $this->relationLoaded('kecamatan') || $this->relationLoaded('desaKelurahan'), [
            'provinsi' => $this->whenLoaded('provinsi', fn () => new ProvinsiResource($this->provinsi)),
            'kabupaten_kota' => $this->whenLoaded('kabupatenKota', fn () => new KabupatenKotaResource($this->kabupatenKota)),
            'kecamatan' => $this->whenLoaded('kecamatan', fn () => new KecamatanResource($this->kecamatan)),
            'desa_kelurahan' => $this->whenLoaded('desaKelurahan', fn () => new DesaKelurahanResource($this->desaKelurahan)),
        ]), 'created_at' => $this->created_at, 'updated_at' => $this->updated_at];
    }
}
