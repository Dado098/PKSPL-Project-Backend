<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProvisioningService extends Model
{
    protected $table = 'provisioning_service';
    protected $primaryKey = 'id_provisioning';
    protected $fillable = ['id_area', 'nama_objek', 'produktivitas', 'harga_pasar', 'luas_pemanfaatan', 'satuan_luas', 'referensi', 'nilai'];

    protected function casts(): array
    {
        return ['produktivitas' => 'decimal:4', 'harga_pasar' => 'decimal:2', 'luas_pemanfaatan' => 'decimal:2', 'nilai' => 'decimal:2'];
    }

    public function getRouteKeyName(): string
    {
        return 'id_provisioning';
    }

    public function areaTerdampak(): BelongsTo
    {
        return $this->belongsTo(AreaTerdampak::class, 'id_area', 'id_area');
    }
}
