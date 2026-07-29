<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Master data role yang dapat diberikan kepada pengguna.
 *
 * Nama role disimpan sebagai nilai tetap karena schema tidak memiliki tabel
 * permission atau relasi role-permission.
 */
class Role extends Model
{
    public const ADMIN = 'admin';

    public const ANALYST = 'analyst';

    public const PENELITI = 'peneliti';

    public const GUEST = 'guest';

    /**
     * Primary key role tidak mengikuti konvensi default Eloquent.
     */
    protected $primaryKey = 'id_role';

    /**
     * @var list<string>
     */
    protected $fillable = ['nama_role', 'deskripsi'];

    /**
     * Mengembalikan daftar role yang diizinkan oleh aplikasi.
     *
     * @return list<string>
     */
    public static function names(): array
    {
        return [self::ADMIN, self::ANALYST, self::PENELITI, self::GUEST];
    }

    /**
     * Menggunakan primary key schema untuk route model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'id_role';
    }

    /**
     * Satu role dapat dimiliki oleh banyak pengguna.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_role', 'id_role');
    }
}
