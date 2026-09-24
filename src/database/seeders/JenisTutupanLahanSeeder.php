<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisTutupanLahanSeeder extends Seeder
{
    public function run(): void
    {
        $index = DB::table('indexes')->where('kode_index', 'A')->value('id_index');

        if ($index !== null) {
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

        $tutupanLahanAntam = [
            [
                'kode_index' => 'IDX-ANTAM-01',
                'nama_tutupan_lahan' => 'Area Reklamasi',
                'kategori' => 'Area Reklamasi',
                'luas' => 250.00,
                'satuan_luas' => 'Hektar',
                'deskripsi' => 'Tutupan lahan vegetasi revegetasi dan reklamasi pascatambang PT Antam.',
            ],
            [
                'kode_index' => 'IDX-ANTAM-02',
                'nama_tutupan_lahan' => 'Hutan Lahan Kering Sekunder',
                'kategori' => 'Hutan Lahan Kering Sekunder',
                'luas' => 650.00,
                'satuan_luas' => 'Hektar',
                'deskripsi' => 'Tutupan vegetasi hutan sekunder alami berkanopi sedang-rapat PT Antam.',
            ],
            [
                'kode_index' => 'IDX-ANTAM-03',
                'nama_tutupan_lahan' => 'Semak Belukar',
                'kategori' => 'Semak Belukar',
                'luas' => 320.00,
                'satuan_luas' => 'Hektar',
                'deskripsi' => 'Tutupan vegetasi semak dan tumbuhan bawah alami PT Antam.',
            ],
            [
                'kode_index' => 'IDX-ANTAM-04',
                'nama_tutupan_lahan' => 'Belukar',
                'kategori' => 'Belukar',
                'luas' => 180.00,
                'satuan_luas' => 'Hektar',
                'deskripsi' => 'Tutupan vegetasi belukar kerapatan sedang pada area transisi PT Antam.',
            ],
            [
                'kode_index' => 'IDX-ANTAM-05',
                'nama_tutupan_lahan' => 'Lahan Terbangun',
                'kategori' => 'Lahan Terbangun',
                'luas' => 140.50,
                'satuan_luas' => 'Hektar',
                'deskripsi' => 'Tutupan lahan fasilitas operasional penambangan, pabrik, dan perkantoran PT Antam.',
            ],
        ];

        foreach ($tutupanLahanAntam as $item) {
            $idIndex = DB::table('indexes')->where('kode_index', $item['kode_index'])->value('id_index');

            if ($idIndex !== null) {
                DB::table('jenis_tutupan_lahan')->updateOrInsert(
                    ['id_index' => $idIndex, 'nama_tutupan_lahan' => $item['nama_tutupan_lahan']],
                    [
                        'kategori' => $item['kategori'],
                        'luas' => $item['luas'],
                        'satuan_luas' => $item['satuan_luas'],
                        'deskripsi' => $item['deskripsi'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
