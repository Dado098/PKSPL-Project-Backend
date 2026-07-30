<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** Menyimpan area ekosistem yang terdampak oleh proyek. */
class AreaTerdampak extends Model
{
    protected $table = 'area_terdampak';
    protected $primaryKey = 'id_area';
    protected $fillable = ['id_proyek', 'id_ekosistem', 'nama_area', 'latitude', 'longitude', 'luas', 'satuan_luas', 'deskripsi'];

    /** Mengubah koordinat dan luas ke presisi numerik yang sesuai. */
    protected function casts(): array
    {
        return ['latitude' => 'decimal:6', 'longitude' => 'decimal:6', 'luas' => 'decimal:2'];
    }

    /** Menggunakan primary key schema untuk route model binding. */
    public function getRouteKeyName(): string
    {
        return 'id_area';
    }

    /** Area terdampak terkait dengan satu proyek. */
    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }

    /** Area terdampak berada pada satu ekosistem. */
    public function ekosistem(): BelongsTo
    {
        return $this->belongsTo(Ekosistem::class, 'id_ekosistem', 'id_ekosistem');
    }

    /** Area terdampak memiliki banyak jasa penyediaan. */
    public function provisioningServices(): HasMany
    {
        return $this->hasMany(ProvisioningService::class, 'id_area', 'id_area');
    }

    /** Area terdampak memiliki banyak jasa pengaturan. */
    public function regulatingServices(): HasMany
    {
        return $this->hasMany(RegulatingService::class, 'id_area', 'id_area');
    }

    /** Area terdampak memiliki banyak jasa pendukung. */
    public function supportingServices(): HasMany
    {
        return $this->hasMany(SupportingService::class, 'id_area', 'id_area');
    }

    /** Area terdampak memiliki banyak jasa budaya. */
    public function culturalServices(): HasMany
    {
        return $this->hasMany(CulturalService::class, 'id_area', 'id_area');
    }

    /** Area terdampak memiliki banyak hasil valuasi. */
    public function hasilValuasi(): HasMany
    {
        return $this->hasMany(HasilValuasi::class, 'id_area', 'id_area');
    }
}
