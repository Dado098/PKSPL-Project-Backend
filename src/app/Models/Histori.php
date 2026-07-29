<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Histori extends Model
{
    protected $table = 'histori';
    protected $primaryKey = 'id_histori';
    public const UPDATED_AT = null;
    protected $fillable = ['id_user', 'id_proyek', 'aktivitas', 'keterangan'];

    public function getRouteKeyName(): string
    {
        return 'id_histori';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }
}
