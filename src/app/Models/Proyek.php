<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proyek extends Model
{
    use HasFactory;
    protected $table = 'proyek';

    protected $primaryKey = 'id_proyek';

    protected $fillable = [
        'id_user',
        'kode_proyek',
        'id_provinsi',
        'id_kabupaten_kota',
        'id_kecamatan',
        'id_desa_kelurahan',
        'nama_proyek',
        'tujuan_valuasi',
        'alamat_lengkap',
        'latitude',
        'longitude',
        'luas',
        'satuan_luas',
        'geometry',
        'shapefile_files',
        'tahun',
        'deskripsi',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tahun' => 'integer',
            'latitude' => 'decimal:6',
            'longitude' => 'decimal:6',
            'luas' => 'decimal:2',
            'geometry' => 'array',
            'shapefile_files' => 'array',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'id_proyek';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Proyek berada pada satu provinsi.
     */
    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'id_provinsi', 'id_provinsi');
    }

    /**
     * Proyek berada pada satu kabupaten atau kota.
     */
    public function kabupatenKota(): BelongsTo
    {
        return $this->belongsTo(KabupatenKota::class, 'id_kabupaten_kota', 'id_kabupaten_kota');
    }

    /**
     * Proyek berada pada satu kecamatan.
     */
    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    /**
     * Proyek berada pada satu desa atau kelurahan.
     */
    public function desaKelurahan(): BelongsTo
    {
        return $this->belongsTo(DesaKelurahan::class, 'id_desa_kelurahan', 'id_desa_kelurahan');
    }

    public function areaTerdampak(): HasMany
    {
        return $this->hasMany(AreaTerdampak::class, 'id_proyek', 'id_proyek');
    }

    public function indexes(): HasMany
    {
        return $this->hasMany(Index::class, 'id_proyek', 'id_proyek');
    }

    public function analisisAi(): HasMany
    {
        return $this->hasMany(AnalisisAi::class, 'id_proyek', 'id_proyek');
    }

    public function histori(): HasMany
    {
        return $this->hasMany(Histori::class, 'id_proyek', 'id_proyek');
    }

    // ===== Review & Discussion Module (additive) =====

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'id_proyek', 'id_proyek');
    }

    // ===== Valuation Integration Module (additive) =====

    public function projectValuationSetting()
    {
        return $this->hasOne(ProjectValuationSetting::class, 'id_proyek', 'id_proyek');
    }

    public function valuationModules(): HasMany
    {
        return $this->hasMany(ValuationModule::class, 'id_proyek', 'id_proyek');
    }

    public function benefits(): HasMany
    {
        return $this->hasMany(Benefit::class, 'id_proyek', 'id_proyek');
    }

    public function costs(): HasMany
    {
        return $this->hasMany(Cost::class, 'id_proyek', 'id_proyek');
    }

    public function eopData(): HasMany
    {
        return $this->hasMany(EopData::class, 'id_proyek', 'id_proyek');
    }

    public function tcmData(): HasMany
    {
        return $this->hasMany(TcmData::class, 'id_proyek', 'id_proyek');
    }

    public function tcmAnalyses(): HasMany
    {
        return $this->hasMany(TcmAnalysis::class, 'id_proyek', 'id_proyek');
    }

    public function cvmData(): HasMany
    {
        return $this->hasMany(CvmData::class, 'id_proyek', 'id_proyek');
    }

    public function cvmAnalyses(): HasMany
    {
        return $this->hasMany(CvmAnalysis::class, 'id_proyek', 'id_proyek');
    }

    public function duvData(): HasMany
    {
        return $this->hasMany(DuvData::class, 'id_proyek', 'id_proyek');
    }

    public function hpmData(): HasMany
    {
        return $this->hasMany(HpmData::class, 'id_proyek', 'id_proyek');
    }

    public function abmData(): HasMany
    {
        return $this->hasMany(AbmData::class, 'id_proyek', 'id_proyek');
    }

    public function ceData(): HasMany
    {
        return $this->hasMany(CeData::class, 'id_proyek', 'id_proyek');
    }
}
