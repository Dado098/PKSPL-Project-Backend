<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Menyimpan nilai jasa budaya pada area terdampak. */
class CulturalService extends Model
{
    protected $table = 'cultural_service';
    protected $primaryKey = 'id_cultural';
    protected $fillable = ['id_area', 'nama_aktivitas', 'jumlah_pengunjung', 'biaya_perjalanan', 'frekuensi', 'referensi', 'nilai'];

    /** Mengubah nilai pengukuran jasa budaya ke presisi numerik. */
    protected function casts(): array
    {
        return ['jumlah_pengunjung' => 'decimal:2', 'biaya_perjalanan' => 'decimal:2', 'frekuensi' => 'decimal:2', 'nilai' => 'decimal:2'];
    }

    /** Menggunakan primary key schema untuk route model binding. */
    public function getRouteKeyName(): string
    {
        return 'id_cultural';
    }

    /** Jasa budaya terkait dengan satu area terdampak. */
    public function areaTerdampak(): BelongsTo
    {
        return $this->belongsTo(AreaTerdampak::class, 'id_area', 'id_area');
    }
}
