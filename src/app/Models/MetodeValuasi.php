<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetodeValuasi extends Model
{
    protected $table = 'metode_valuasi';
    protected $primaryKey = 'id_metode';
    protected $fillable = ['nama_metode', 'deskripsi', 'formula', 'parameter', 'status'];

    public function getRouteKeyName(): string
    {
        return 'id_metode';
    }

    public function hasilValuasi(): HasMany
    {
        return $this->hasMany(HasilValuasi::class, 'id_metode', 'id_metode');
    }
}
