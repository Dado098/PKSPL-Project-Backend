<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisTutupanLahanSeeder extends Seeder
{
    public function run(): void
    {
        $index = DB::table('indexes')->where('kode_index', 'A')->value('id_index');

        if ($index === null) {
            return;
        }

        DB::table('jenis_tutupan_lahan')->updateOrInsert(
            ['id_index' => $index, 'nama_tutupan_lahan' => 'Mangrove'],
            [
                'kategori' => 'Ekosistem Pesisir',
                'luas' => 45.50,
                'satuan_luas' => 'Hektar',
                'deskripsi' => 'Tutupan lahan contoh untuk alur valuasi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
    }
}
