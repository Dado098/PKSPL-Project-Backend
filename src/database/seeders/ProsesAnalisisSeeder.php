<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/** Mengisi hasil valuasi, analisis AI, histori, dan validasi contoh. */
class ProsesAnalisisSeeder extends Seeder
{
    /** Menyimpan data proses yang memiliki referensi valid ke proyek dan user. */
    public function run(): void
    {
        $analyst = DB::table('users')->where('email', 'analyst@pkspl.test')->value('id_user');
        $peneliti = DB::table('users')->where('email', 'peneliti@pkspl.test')->value('id_user');
        $metode = DB::table('metode_valuasi')->where('nama_metode', 'Harga Pasar')->value('id_metode');

        foreach (DB::table('area_terdampak')->get() as $area) {
            DB::table('hasil_valuasi')->updateOrInsert(['id_area' => $area->id_area, 'id_metode' => $metode], ['direct_use_value' => 218750, 'indirect_use_value' => 150000000, 'option_value' => 25000000, 'existence_value' => 50000000, 'bequest_value' => 15000000, 'tev' => 240218750, 'tanggal_hitung' => now(), 'keterangan' => 'Nilai contoh berdasarkan data seeder.']);
            $hasil = DB::table('hasil_valuasi')->where('id_area', $area->id_area)->where('id_metode', $metode)->value('id_hasil');
            DB::table('validasi_analyst')->updateOrInsert(['id_hasil' => $hasil, 'id_user' => $analyst], ['status_validasi' => 'Valid', 'metode_analisis' => 'Manual', 'catatan' => 'Data contoh telah diperiksa analyst.', 'tanggal_validasi' => now()]);
        }

        foreach (DB::table('proyek')->orderBy('id_proyek')->get() as $index => $proyek) {
            $tipe = ['Ringkasan', 'Rekomendasi', 'Prediksi'][$index];
            $pertanyaan = ['Apa manfaat utama ekosistem pada area ini?', 'Tindakan pengelolaan apa yang perlu diprioritaskan?', 'Bagaimana kecenderungan nilai ekonomi area ini?'][$index];
            DB::table('analisis_ai')->updateOrInsert(['id_proyek' => $proyek->id_proyek, 'id_user' => $peneliti, 'pertanyaan' => $pertanyaan], ['jawaban' => 'Analisis contoh menggunakan data proyek, area terdampak, dan referensi yang tersedia.', 'sumber_data' => 'Knowledge Base Valuasi Ekosistem', 'tipe_analisis' => $tipe]);
            DB::table('histori')->updateOrInsert(['id_user' => $peneliti, 'id_proyek' => $proyek->id_proyek, 'aktivitas' => 'Membuat proyek valuasi'], ['keterangan' => 'Proyek contoh dibuat melalui proses seeding.']);
        }
    }
}
