<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Menyimpan hasil validasi analyst terhadap hasil valuasi. */
class ValidasiAnalyst extends Model
{
    protected $table = 'validasi_analyst';
    protected $primaryKey = 'id_validasi';
    protected $fillable = ['id_hasil', 'id_user', 'status_validasi', 'metode_analisis', 'catatan', 'tanggal_validasi'];

    /** Mengubah tanggal validasi ke objek tanggal dan waktu. */
    protected function casts(): array
    {
        return ['tanggal_validasi' => 'datetime'];
    }

    /** Menggunakan primary key schema untuk route model binding. */
    public function getRouteKeyName(): string
    {
        return 'id_validasi';
    }

    /** Validasi terkait dengan satu hasil valuasi. */
    public function hasilValuasi(): BelongsTo
    {
        return $this->belongsTo(HasilValuasi::class, 'id_hasil', 'id_hasil');
    }

    /** Validasi dilakukan oleh satu pengguna. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
