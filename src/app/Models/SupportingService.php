<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Menyimpan nilai jasa pendukung pada area terdampak. */
class SupportingService extends Model
{
    protected $table = 'supporting_service';
    protected $primaryKey = 'id_supporting';
    protected $fillable = ['id_area', 'fungsi_pendukung', 'deskripsi', 'referensi', 'nilai', 'kategori_tev', 'id_provinsi', 'id_kabupaten_kota', 'id_kecamatan', 'id_desa_kelurahan'];

    /** Mengubah nilai ekonomi jasa pendukung ke presisi numerik. */
    protected function casts(): array
    {
        return ['nilai' => 'decimal:2'];
    }

    /** Menggunakan primary key schema untuk route model binding. */
    public function getRouteKeyName(): string
    {
        return 'id_supporting';
    }

    /** Jasa pendukung terkait dengan satu area terdampak. */
    public function areaTerdampak(): BelongsTo
    {
        return $this->belongsTo(AreaTerdampak::class, 'id_area', 'id_area');
    }

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'id_provinsi', 'id_provinsi');
    }

    public function kabupatenKota(): BelongsTo
    {
        return $this->belongsTo(KabupatenKota::class, 'id_kabupaten_kota', 'id_kabupaten_kota');
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    public function desaKelurahan(): BelongsTo
    {
        return $this->belongsTo(DesaKelurahan::class, 'id_desa_kelurahan', 'id_desa_kelurahan');
    }
}
