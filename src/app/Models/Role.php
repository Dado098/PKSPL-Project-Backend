<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $primaryKey = 'id_role';

    protected $fillable = ['nama_role', 'deskripsi'];

    public function getRouteKeyName(): string
    {
        return 'id_role';
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_role', 'id_role');
    }
}
