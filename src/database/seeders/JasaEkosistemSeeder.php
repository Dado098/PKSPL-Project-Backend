<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/** Mengisi contoh jasa ekosistem untuk setiap jenis tutupan lahan. */
class JasaEkosistemSeeder extends Seeder
{
    public function run(): void
    {
        $jenisTutupanLahan = DB::table('jenis_tutupan_lahan')->orderBy('id_jenis_tutupan_lahan')->get();

        foreach ($jenisTutupanLahan as $tutupanLahan) {
            $proyek = DB::table('indexes')
                ->join('proyek', 'proyek.id_proyek', '=', 'indexes.id_proyek')
                ->where('indexes.id_index', $tutupanLahan->id_index)
                ->select('proyek.*')
                ->first();

            $wilayah = [
                'id_provinsi' => $proyek?->id_provinsi,
                'id_kabupaten_kota' => $proyek?->id_kabupaten_kota,
                'id_kecamatan' => $proyek?->id_kecamatan,
                'id_desa_kelurahan' => $proyek?->id_desa_kelurahan,
            ];

            DB::table('provisioning_service')->updateOrInsert(
                ['id_jenis_tutupan_lahan' => $tutupanLahan->id_jenis_tutupan_lahan, 'nama_objek' => 'Hasil perikanan lokal'],
                array_merge([
                    'produktivitas' => 1.2500,
                    'harga_pasar' => 35000,
                    'luas_pemanfaatan' => 5,
                    'satuan_luas' => 'Hektar',
                    'referensi' => 'Survei lapangan 2026',
                    'nilai' => 218750,
                    'kategori_tev' => 'DUV',
                ], $wilayah)
            );

            DB::table('regulating_service')->updateOrInsert(
                ['id_jenis_tutupan_lahan' => $tutupanLahan->id_jenis_tutupan_lahan, 'jenis_regulating' => 'Perlindungan pesisir'],
                array_merge([
                    'indikator' => 'Luas tutupan',
                    'satuan' => 'Hektar',
                    'nilai_indikator' => 10,
                    'referensi' => 'Peta tutupan lahan',
                    'nilai' => 150000000,
                    'kategori_tev' => 'IUV',
                ], $wilayah)
            );

            DB::table('supporting_service')->updateOrInsert(
                ['id_jenis_tutupan_lahan' => $tutupanLahan->id_jenis_tutupan_lahan, 'fungsi_pendukung' => 'Habitat dan siklus nutrien'],
                array_merge([
                    'deskripsi' => 'Menopang keanekaragaman hayati.',
                    'referensi' => 'Kajian ekologi 2025',
                    'nilai' => 75000000,
                    'kategori_tev' => 'OV',
                ], $wilayah)
            );

            DB::table('cultural_service')->updateOrInsert(
                ['id_jenis_tutupan_lahan' => $tutupanLahan->id_jenis_tutupan_lahan, 'nama_aktivitas' => 'Wisata edukasi ekosistem'],
                array_merge([
                    'jumlah_pengunjung' => 1200,
                    'biaya_perjalanan' => 150000,
                    'frekuensi' => 1,
                    'referensi' => 'Survei pengunjung 2026',
                    'nilai' => 180000000,
                    'kategori_tev' => 'EV',
                ], $wilayah)
            );
        }
    }
}
