<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Mengisi akun contoh untuk setiap role aplikasi.
 */
class UserSeeder extends Seeder
{
    /**
     * Menyimpan akun pengembangan dengan role yang konsisten.
     */
    public function run(): void
    {
        foreach ([
            ['admin@gmail.com', 'Administrator PKSPL', Role::ADMIN],
            ['analyst@gmail.com', 'Analyst PKSPL', Role::ANALYST],
            ['peneliti@gmail.com', 'Bima Saputra', Role::PENELITI],
            ['guest@gmail.com', 'Tamu PKSPL', Role::GUEST],
        ] as [$email, $nama, $role]) {

            User::query()->updateOrCreate(
                ['email' => $email],
                [
                    'id_role' => Role::query()
                        ->where('nama_role', $role)
                        ->valueOrFail('id_role'),
                    'nama' => $nama,
                    'password' => 'password',
                    'status' => 'Aktif',
                ]
            );
        }
    }
}
