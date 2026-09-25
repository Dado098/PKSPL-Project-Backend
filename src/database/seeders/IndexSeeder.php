<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndexSeeder extends Seeder
{
    public function run(): void
    {
        $proyek = DB::table('proyek')->where('nama_proyek', 'Revitalisasi Mangrove Teluk Benoa')->value('id_proyek');

        if ($proyek !== null) {
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

        $proyekAntam = DB::table('proyek')->where('kode_proyek', 'PRJ-ANTAM')->value('id_proyek')
            ?? DB::table('proyek')->where('nama_proyek', 'like', '%Antam%')->value('id_proyek');

        if ($proyekAntam !== null) {
            $indexesAntam = [
                [
                    'kode_index' => 'IDX-ANTAM-01',
                    'nama_index' => 'Area Reklamasi',
                    'luas' => 250.00,
                    'satuan_luas' => 'Hektar',
                    'deskripsi' => 'Zona revegetasi dan reklamasi lahan pascatambang PT Antam.',
                ],
                [
                    'kode_index' => 'IDX-ANTAM-02',
                    'nama_index' => 'Hutan Lahan Kering Sekunder',
                    'luas' => 650.00,
                    'satuan_luas' => 'Hektar',
                    'deskripsi' => 'Kawasan hutan alam sekunder lahan kering di sekitar IUP PT Antam.',
                ],
                [
                    'kode_index' => 'IDX-ANTAM-03',
                    'nama_index' => 'Semak Belukar',
                    'luas' => 320.00,
                    'satuan_luas' => 'Hektar',
                    'deskripsi' => 'Zona vegetasi semak belukar alami dan transisi suksesi.',
                ],
                [
                    'kode_index' => 'IDX-ANTAM-04',
                    'nama_index' => 'Belukar',
                    'luas' => 180.00,
                    'satuan_luas' => 'Hektar',
                    'deskripsi' => 'Kawasan tutupan belukar kerapatan sedang pasca gangguan.',
                ],
                [
                    'kode_index' => 'IDX-ANTAM-05',
                    'nama_index' => 'Lahan Terbangun',
                    'luas' => 140.50,
                    'satuan_luas' => 'Hektar',
                    'deskripsi' => 'Kawasan fasilitas operasional, pabrik pengolahan, dan perkantoran PT Antam.',
                ],
            ];

            foreach ($indexesAntam as $item) {
                DB::table('indexes')->updateOrInsert(
                    ['id_proyek' => $proyekAntam, 'kode_index' => $item['kode_index']],
                    array_merge($item, [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ])
                );
            }
        }
    }
}
