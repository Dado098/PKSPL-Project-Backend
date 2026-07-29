<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalisisAi extends Model
{
    protected $table = 'analisis_ai';
    protected $primaryKey = 'id_analisis';
    public const UPDATED_AT = null;
    protected $fillable = ['id_proyek', 'id_user', 'pertanyaan', 'jawaban', 'sumber_data', 'tipe_analisis'];

    public function getRouteKeyName(): string
    {
        return 'id_analisis';
    }

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
