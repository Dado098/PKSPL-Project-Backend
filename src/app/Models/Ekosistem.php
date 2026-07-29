<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ekosistem extends Model
{
    protected $table = 'ekosistem';
    protected $primaryKey = 'id_ekosistem';
    protected $fillable = ['nama_ekosistem', 'deskripsi', 'status'];

    public function getRouteKeyName(): string
    {
        return 'id_ekosistem';
    }

    public function areaTerdampak(): HasMany
    {
        return $this->hasMany(AreaTerdampak::class, 'id_ekosistem', 'id_ekosistem');
    }
}
