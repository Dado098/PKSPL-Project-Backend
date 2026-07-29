<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HasilValuasi extends Model
{
    protected $table = 'hasil_valuasi';
    protected $primaryKey = 'id_hasil';
    protected $fillable = ['id_area', 'id_metode', 'direct_use_value', 'indirect_use_value', 'option_value', 'existence_value', 'bequest_value', 'tev', 'tanggal_hitung', 'keterangan'];

    protected function casts(): array
    {
        return [
            'direct_use_value' => 'decimal:2',
            'indirect_use_value' => 'decimal:2',
            'option_value' => 'decimal:2',
            'existence_value' => 'decimal:2',
            'bequest_value' => 'decimal:2',
            'tev' => 'decimal:2',
            'tanggal_hitung' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'id_hasil';
    }

    public function areaTerdampak(): BelongsTo
    {
        return $this->belongsTo(AreaTerdampak::class, 'id_area', 'id_area');
    }

    public function metodeValuasi(): BelongsTo
    {
        return $this->belongsTo(MetodeValuasi::class, 'id_metode', 'id_metode');
    }

    public function validasiAnalyst(): HasMany
    {
        return $this->hasMany(ValidasiAnalyst::class, 'id_hasil', 'id_hasil');
    }
}
