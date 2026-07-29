<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValidasiAnalyst extends Model
{
    protected $table = 'validasi_analyst';
    protected $primaryKey = 'id_validasi';
    protected $fillable = ['id_hasil', 'id_user', 'status_validasi', 'metode_analisis', 'catatan', 'tanggal_validasi'];

    protected function casts(): array
    {
        return ['tanggal_validasi' => 'datetime'];
    }

    public function getRouteKeyName(): string
    {
        return 'id_validasi';
    }

    public function hasilValuasi(): BelongsTo
    {
        return $this->belongsTo(HasilValuasi::class, 'id_hasil', 'id_hasil');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
