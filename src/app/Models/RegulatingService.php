<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegulatingService extends Model
{
    protected $table = 'regulating_service';
    protected $primaryKey = 'id_regulating';
    protected $fillable = ['id_area', 'jenis_regulating', 'indikator', 'satuan', 'nilai_indikator', 'referensi', 'nilai'];

    protected function casts(): array
    {
        return ['nilai_indikator' => 'decimal:4', 'nilai' => 'decimal:2'];
    }

    public function getRouteKeyName(): string
    {
        return 'id_regulating';
    }

    public function areaTerdampak(): BelongsTo
    {
        return $this->belongsTo(AreaTerdampak::class, 'id_area', 'id_area');
    }
}
