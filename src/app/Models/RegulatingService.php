<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Menyimpan nilai jasa pengaturan pada area terdampak. */
class RegulatingService extends Model
{
    protected $table = 'regulating_service';
    protected $primaryKey = 'id_regulating';
    protected $fillable = ['id_area', 'jenis_regulating', 'indikator', 'satuan', 'nilai_indikator', 'referensi', 'nilai'];

    /** Mengubah nilai indikator dan ekonomi ke presisi numerik. */
    protected function casts(): array
    {
        return ['nilai_indikator' => 'decimal:4', 'nilai' => 'decimal:2'];
    }

    /** Menggunakan primary key schema untuk route model binding. */
    public function getRouteKeyName(): string
    {
        return 'id_regulating';
    }

    /** Jasa pengaturan terkait dengan satu area terdampak. */
    public function areaTerdampak(): BelongsTo
    {
        return $this->belongsTo(AreaTerdampak::class, 'id_area', 'id_area');
    }
}
