<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proyek extends Model
{
    protected $table = 'proyek';
    protected $primaryKey = 'id_proyek';
    protected $fillable = ['id_user', 'nama_proyek', 'tujuan_valuasi', 'lokasi', 'tahun', 'deskripsi', 'status'];

    protected function casts(): array
    {
        return ['tahun' => 'integer'];
    }

    public function getRouteKeyName(): string
    {
        return 'id_proyek';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function areaTerdampak(): HasMany
    {
        return $this->hasMany(AreaTerdampak::class, 'id_proyek', 'id_proyek');
    }

    public function analisisAi(): HasMany
    {
        return $this->hasMany(AnalisisAi::class, 'id_proyek', 'id_proyek');
    }

    public function histori(): HasMany
    {
        return $this->hasMany(Histori::class, 'id_proyek', 'id_proyek');
    }
}
