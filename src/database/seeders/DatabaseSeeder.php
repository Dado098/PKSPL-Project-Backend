<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Menjalankan seed data master yang dibutuhkan aplikasi.
     */
    public function run(): void
    {
        // Role harus tersedia sebelum user dibuat karena users.id_role wajib diisi.
        $this->call(RoleSeeder::class);
    }
}
