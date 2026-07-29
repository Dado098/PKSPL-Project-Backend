<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AreaTerdampak extends Model
{
    protected $table = 'area_terdampak';
    protected $primaryKey = 'id_area';
    protected $fillable = ['id_proyek', 'id_ekosistem', 'nama_area', 'latitude', 'longitude', 'luas', 'satuan_luas', 'deskripsi'];

    protected function casts(): array
    {
        return ['latitude' => 'decimal:6', 'longitude' => 'decimal:6', 'luas' => 'decimal:2'];
    }

    public function getRouteKeyName(): string
    {
        return 'id_area';
    }

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }

    public function ekosistem(): BelongsTo
    {
        return $this->belongsTo(Ekosistem::class, 'id_ekosistem', 'id_ekosistem');
    }

    public function provisioningServices(): HasMany
    {
        return $this->hasMany(ProvisioningService::class, 'id_area', 'id_area');
    }

    public function regulatingServices(): HasMany
    {
        return $this->hasMany(RegulatingService::class, 'id_area', 'id_area');
    }

    public function supportingServices(): HasMany
    {
        return $this->hasMany(SupportingService::class, 'id_area', 'id_area');
    }

    public function culturalServices(): HasMany
    {
        return $this->hasMany(CulturalService::class, 'id_area', 'id_area');
    }

    public function hasilValuasi(): HasMany
    {
        return $this->hasMany(HasilValuasi::class, 'id_area', 'id_area');
    }
}
