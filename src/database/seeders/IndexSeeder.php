<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndexSeeder extends Seeder
{
    public function run(): void
    {
        $proyek = DB::table('proyek')->where('nama_proyek', 'Revitalisasi Mangrove Teluk Benoa')->value('id_proyek');

        if ($proyek === null) {
            return;
        }

        DB::table('indexes')->updateOrInsert(
            ['id_proyek' => $proyek, 'kode_index' => 'A'],
            [
                'nama_index' => 'Index Pesisir',
                'luas' => 45.50,
                'satuan_luas' => 'Hektar',
                'deskripsi' => 'Pembagian area pesisir untuk data contoh.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
    }
}
