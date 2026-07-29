<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportingService extends Model
{
    protected $table = 'supporting_service';
    protected $primaryKey = 'id_supporting';
    protected $fillable = ['id_area', 'fungsi_pendukung', 'deskripsi', 'referensi', 'nilai'];

    protected function casts(): array
    {
        return ['nilai' => 'decimal:2'];
    }

    public function getRouteKeyName(): string
    {
        return 'id_supporting';
    }

    public function areaTerdampak(): BelongsTo
    {
        return $this->belongsTo(AreaTerdampak::class, 'id_area', 'id_area');
    }
}
