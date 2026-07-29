<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CulturalService extends Model
{
    protected $table = 'cultural_service';
    protected $primaryKey = 'id_cultural';
    protected $fillable = ['id_area', 'nama_aktivitas', 'jumlah_pengunjung', 'biaya_perjalanan', 'frekuensi', 'referensi', 'nilai'];

    protected function casts(): array
    {
        return ['jumlah_pengunjung' => 'decimal:2', 'biaya_perjalanan' => 'decimal:2', 'frekuensi' => 'decimal:2', 'nilai' => 'decimal:2'];
    }

    public function getRouteKeyName(): string
    {
        return 'id_cultural';
    }

    public function areaTerdampak(): BelongsTo
    {
        return $this->belongsTo(AreaTerdampak::class, 'id_area', 'id_area');
    }
}
