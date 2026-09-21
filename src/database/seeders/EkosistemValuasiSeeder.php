<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ValuasiEkosistem;
use App\Models\ValuasiKategoriJasa;
use App\Models\ValuasiItemValuasi;
use App\Models\IndexEkosistemSummary;

/**
 * Seeder data valuasi Total Economic Value (TEV) untuk 5 tipe ekosistem.
 *
 * Struktur per ekosistem:
 *   A. Provisioning Services
 *      A.1 Flora
 *      A.2 Fauna
 *   B. Regulating Services
 *   C. Supporting Services
 *   D. Cultural Services
 */
class EkosistemValuasiSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama jika ada (idempotent) — PostgreSQL CASCADE
        try {
            DB::statement('TRUNCATE TABLE index_ekosistem_summary RESTART IDENTITY CASCADE');
            DB::statement('TRUNCATE TABLE valuasi_item_valuasis RESTART IDENTITY CASCADE');
            DB::statement('TRUNCATE TABLE valuasi_kategori_jasas RESTART IDENTITY CASCADE');
            DB::statement('TRUNCATE TABLE valuasi_ekosistems RESTART IDENTITY CASCADE');
        } catch (\Throwable) {
            // Tabel belum ada pada run pertama — abaikan
        }

        $ekosistemData = $this->getEkosistemData();

        foreach ($ekosistemData as $ekoData) {
            // Hitung TEV dari subtotal semua kategori
            $tevEkosistem = collect($ekoData['kategori'])->sum('subtotal');

            $ekosistem = ValuasiEkosistem::create([
                'nama_ekosistem' => $ekoData['nama'],
                'tev_ekosistem'  => $tevEkosistem,
            ]);

            foreach ($ekoData['kategori'] as $katData) {
                $kategori = ValuasiKategoriJasa::create([
                    'valuasi_ekosistem_id' => $ekosistem->id,
                    'nama_kategori'        => $katData['nama'],
                    'kode_kategori'        => $katData['kode'],
                    'subtotal_kategori'    => $katData['subtotal'],
                ]);

                foreach ($katData['items'] as $item) {
                    ValuasiItemValuasi::create([
                        'valuasi_kategori_jasa_id' => $kategori->id,
                        'no'                       => $item['no'],
                        'nama_item'                => $item['nama_item'],
                        'nama_latin'               => $item['nama_latin'] ?? null,
                        'nama_daerah'              => $item['nama_daerah'] ?? null,
                        'total_nilai_ekonomi'      => $item['total_nilai_ekonomi'],
                        'kategori'                 => $item['kategori'],
                    ]);
                }
            }

            // Isi tabel ringkasan indeks
            foreach ($ekoData['kategori'] as $katData) {
                IndexEkosistemSummary::create([
                    'nama_ekosistem' => $ekoData['nama'],
                    'kategori'       => $katData['nama'],
                    'jumlah_rp'      => $katData['subtotal'],
                    'tev_total'      => $tevEkosistem,
                ]);
            }
        }
    }

    /**
     * Data valuasi lengkap untuk 5 tipe ekosistem.
     */
    private function getEkosistemData(): array
    {
        return [
            // ============================================================
            // 1. AREA REKLAMASI
            // ============================================================
            [
                'nama' => 'Area Reklamasi',
                'kategori' => [
                    [
                        'nama' => 'Provisioning',
                        'kode' => 'A',
                        'subtotal' => 1_245_680_000.00,
                        'items' => [
                            // A.1 Flora
                            [
                                'no' => 'A.1.1',
                                'nama_item' => 'Cemara laut',
                                'nama_latin' => 'Casuarina equisetifolia',
                                'nama_daerah' => 'Cemara udang',
                                'total_nilai_ekonomi' => 185_400_000.00,
                                'kategori' => 'provisioning_flora',
                            ],
                            [
                                'no' => 'A.1.2',
                                'nama_item' => 'Akasia',
                                'nama_latin' => 'Acacia mangium',
                                'nama_daerah' => 'Akasia daun lebar',
                                'total_nilai_ekonomi' => 142_350_000.00,
                                'kategori' => 'provisioning_flora',
                            ],
                            [
                                'no' => 'A.1.3',
                                'nama_item' => 'Trembesi',
                                'nama_latin' => 'Samanea saman',
                                'nama_daerah' => 'Ki hujan',
                                'total_nilai_ekonomi' => 98_750_000.00,
                                'kategori' => 'provisioning_flora',
                            ],
                            [
                                'no' => 'A.1.4',
                                'nama_item' => 'Sengon',
                                'nama_latin' => 'Paraserianthes falcataria',
                                'nama_daerah' => 'Jeunjing',
                                'total_nilai_ekonomi' => 167_280_000.00,
                                'kategori' => 'provisioning_flora',
                            ],
                            // A.2 Fauna
                            [
                                'no' => 'A.2.1',
                                'nama_item' => 'Babi hutan',
                                'nama_latin' => 'Sus scrofa',
                                'nama_daerah' => 'Celeng',
                                'total_nilai_ekonomi' => 215_600_000.00,
                                'kategori' => 'provisioning_fauna',
                            ],
                            [
                                'no' => 'A.2.2',
                                'nama_item' => 'Ayam hutan',
                                'nama_latin' => 'Gallus varius',
                                'nama_daerah' => 'Ayam alas',
                                'total_nilai_ekonomi' => 124_800_000.00,
                                'kategori' => 'provisioning_fauna',
                            ],
                            [
                                'no' => 'A.2.3',
                                'nama_item' => 'Lebah madu',
                                'nama_latin' => 'Apis cerana',
                                'nama_daerah' => 'Tawon madu',
                                'total_nilai_ekonomi' => 311_500_000.00,
                                'kategori' => 'provisioning_fauna',
                            ],
                        ],
                    ],
                    [
                        'nama' => 'Regulating',
                        'kode' => 'B',
                        'subtotal' => 856_420_000.00,
                        'items' => [
                            [
                                'no' => 'B.1',
                                'nama_item' => 'Pencegah erosi',
                                'total_nilai_ekonomi' => 325_180_000.00,
                                'kategori' => 'regulating',
                            ],
                            [
                                'no' => 'B.2',
                                'nama_item' => 'Pengatur tata air',
                                'total_nilai_ekonomi' => 287_640_000.00,
                                'kategori' => 'regulating',
                            ],
                            [
                                'no' => 'B.3',
                                'nama_item' => 'Penyerap karbon',
                                'total_nilai_ekonomi' => 243_600_000.00,
                                'kategori' => 'regulating',
                            ],
                        ],
                    ],
                    [
                        'nama' => 'Supporting',
                        'kode' => 'C',
                        'subtotal' => 425_300_000.00,
                        'items' => [
                            [
                                'no' => 'C.1',
                                'nama_item' => 'Pembentukan tanah',
                                'total_nilai_ekonomi' => 198_500_000.00,
                                'kategori' => 'supporting',
                            ],
                            [
                                'no' => 'C.2',
                                'nama_item' => 'Siklus hara',
                                'total_nilai_ekonomi' => 226_800_000.00,
                                'kategori' => 'supporting',
                            ],
                        ],
                    ],
                    [
                        'nama' => 'Cultural',
                        'kode' => 'D',
                        'subtotal' => 312_750_000.00,
                        'items' => [
                            [
                                'no' => 'D.1',
                                'nama_item' => 'Wisata edukasi reklamasi',
                                'total_nilai_ekonomi' => 187_250_000.00,
                                'kategori' => 'cultural',
                            ],
                            [
                                'no' => 'D.2',
                                'nama_item' => 'Penelitian dan pendidikan',
                                'total_nilai_ekonomi' => 125_500_000.00,
                                'kategori' => 'cultural',
                            ],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // 2. HUTAN LAHAN KERING SEKUNDER
            // ============================================================
            [
                'nama' => 'Hutan Lahan Kering Sekunder',
                'kategori' => [
                    [
                        'nama' => 'Provisioning',
                        'kode' => 'A',
                        'subtotal' => 4_687_350_000.00,
                        'items' => [
                            // A.1 Flora
                            [
                                'no' => 'A.1.1',
                                'nama_item' => 'Meranti',
                                'nama_latin' => 'Shorea leprosula',
                                'nama_daerah' => 'Meranti tembaga',
                                'total_nilai_ekonomi' => 856_200_000.00,
                                'kategori' => 'provisioning_flora',
                            ],
                            [
                                'no' => 'A.1.2',
                                'nama_item' => 'Jati',
                                'nama_latin' => 'Tectona grandis',
                                'nama_daerah' => 'Jati emas',
                                'total_nilai_ekonomi' => 1_245_000_000.00,
                                'kategori' => 'provisioning_flora',
                            ],
                            [
                                'no' => 'A.1.3',
                                'nama_item' => 'Mahoni',
                                'nama_latin' => 'Swietenia macrophylla',
                                'nama_daerah' => 'Mahoni daun lebar',
                                'total_nilai_ekonomi' => 678_450_000.00,
                                'kategori' => 'provisioning_flora',
                            ],
                            [
                                'no' => 'A.1.4',
                                'nama_item' => 'Rotan',
                                'nama_latin' => 'Calamus caesius',
                                'nama_daerah' => 'Rotan sega',
                                'total_nilai_ekonomi' => 432_100_000.00,
                                'kategori' => 'provisioning_flora',
                            ],
                            [
                                'no' => 'A.1.5',
                                'nama_item' => 'Bambu',
                                'nama_latin' => 'Bambusa vulgaris',
                                'nama_daerah' => 'Bambu kuning',
                                'total_nilai_ekonomi' => 287_600_000.00,
                                'kategori' => 'provisioning_flora',
                            ],
                            // A.2 Fauna
                            [
                                'no' => 'A.2.1',
                                'nama_item' => 'Rusa sambar',
                                'nama_latin' => 'Rusa unicolor',
                                'nama_daerah' => 'Menjangan',
                                'total_nilai_ekonomi' => 345_800_000.00,
                                'kategori' => 'provisioning_fauna',
                            ],
                            [
                                'no' => 'A.2.2',
                                'nama_item' => 'Babi hutan',
                                'nama_latin' => 'Sus scrofa',
                                'nama_daerah' => 'Celeng',
                                'total_nilai_ekonomi' => 298_400_000.00,
                                'kategori' => 'provisioning_fauna',
                            ],
                            [
                                'no' => 'A.2.3',
                                'nama_item' => 'Lebah madu hutan',
                                'nama_latin' => 'Apis dorsata',
                                'nama_daerah' => 'Tawon gung',
                                'total_nilai_ekonomi' => 356_200_000.00,
                                'kategori' => 'provisioning_fauna',
                            ],
                            [
                                'no' => 'A.2.4',
                                'nama_item' => 'Kijang',
                                'nama_latin' => 'Muntiacus muntjak',
                                'nama_daerah' => 'Kidang',
                                'total_nilai_ekonomi' => 187_600_000.00,
                                'kategori' => 'provisioning_fauna',
                            ],
                        ],
                    ],
                    [
                        'nama' => 'Regulating',
                        'kode' => 'B',
                        'subtotal' => 3_842_500_000.00,
                        'items' => [
                            [
                                'no' => 'B.1',
                                'nama_item' => 'Penyerap karbon',
                                'total_nilai_ekonomi' => 1_285_400_000.00,
                                'kategori' => 'regulating',
                            ],
                            [
                                'no' => 'B.2',
                                'nama_item' => 'Pengatur tata air / hidrologi',
                                'total_nilai_ekonomi' => 978_600_000.00,
                                'kategori' => 'regulating',
                            ],
                            [
                                'no' => 'B.3',
                                'nama_item' => 'Pencegah erosi dan longsor',
                                'total_nilai_ekonomi' => 865_300_000.00,
                                'kategori' => 'regulating',
                            ],
                            [
                                'no' => 'B.4',
                                'nama_item' => 'Pengatur iklim mikro',
                                'total_nilai_ekonomi' => 713_200_000.00,
                                'kategori' => 'regulating',
                            ],
                        ],
                    ],
                    [
                        'nama' => 'Supporting',
                        'kode' => 'C',
                        'subtotal' => 2_456_800_000.00,
                        'items' => [
                            [
                                'no' => 'C.1',
                                'nama_item' => 'Habitat keanekaragaman hayati',
                                'total_nilai_ekonomi' => 1_124_300_000.00,
                                'kategori' => 'supporting',
                            ],
                            [
                                'no' => 'C.2',
                                'nama_item' => 'Pembentukan tanah',
                                'total_nilai_ekonomi' => 687_500_000.00,
                                'kategori' => 'supporting',
                            ],
                            [
                                'no' => 'C.3',
                                'nama_item' => 'Siklus hara dan dekomposisi',
                                'total_nilai_ekonomi' => 645_000_000.00,
                                'kategori' => 'supporting',
                            ],
                        ],
                    ],
                    [
                        'nama' => 'Cultural',
                        'kode' => 'D',
                        'subtotal' => 1_578_250_000.00,
                        'items' => [
                            [
                                'no' => 'D.1',
                                'nama_item' => 'Ekowisata hutan',
                                'total_nilai_ekonomi' => 645_750_000.00,
                                'kategori' => 'cultural',
                            ],
                            [
                                'no' => 'D.2',
                                'nama_item' => 'Nilai spiritual dan budaya',
                                'total_nilai_ekonomi' => 432_500_000.00,
                                'kategori' => 'cultural',
                            ],
                            [
                                'no' => 'D.3',
                                'nama_item' => 'Penelitian dan pendidikan',
                                'total_nilai_ekonomi' => 500_000_000.00,
                                'kategori' => 'cultural',
                            ],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // 3. SEMAK BELUKAR
            // ============================================================
            [
                'nama' => 'Semak Belukar',
                'kategori' => [
                    [
                        'nama' => 'Provisioning',
                        'kode' => 'A',
                        'subtotal' => 1_432_850_000.00,
                        'items' => [
                            // A.1 Flora
                            [
                                'no' => 'A.1.1',
                                'nama_item' => 'Alang-alang',
                                'nama_latin' => 'Imperata cylindrica',
                                'nama_daerah' => 'Eurih',
                                'total_nilai_ekonomi' => 165_200_000.00,
                                'kategori' => 'provisioning_flora',
                            ],
                            [
                                'no' => 'A.1.2',
                                'nama_item' => 'Kirinyuh',
                                'nama_latin' => 'Chromolaena odorata',
                                'nama_daerah' => 'Kopasanda',
                                'total_nilai_ekonomi' => 87_450_000.00,
                                'kategori' => 'provisioning_flora',
                            ],
                            [
                                'no' => 'A.1.3',
                                'nama_item' => 'Senduduk',
                                'nama_latin' => 'Melastoma malabathricum',
                                'nama_daerah' => 'Senggani',
                                'total_nilai_ekonomi' => 124_600_000.00,
                                'kategori' => 'provisioning_flora',
                            ],
                            [
                                'no' => 'A.1.4',
                                'nama_item' => 'Turi',
                                'nama_latin' => 'Sesbania grandiflora',
                                'nama_daerah' => 'Turi putih',
                                'total_nilai_ekonomi' => 198_300_000.00,
                                'kategori' => 'provisioning_flora',
                            ],
                            // A.2 Fauna
                            [
                                'no' => 'A.2.1',
                                'nama_item' => 'Babi hutan',
                                'nama_latin' => 'Sus scrofa',
                                'nama_daerah' => 'Celeng',
                                'total_nilai_ekonomi' => 287_400_000.00,
                                'kategori' => 'provisioning_fauna',
                            ],
                            [
                                'no' => 'A.2.2',
                                'nama_item' => 'Ayam hutan hijau',
                                'nama_latin' => 'Gallus varius',
                                'nama_daerah' => 'Ayam bekisar',
                                'total_nilai_ekonomi' => 245_600_000.00,
                                'kategori' => 'provisioning_fauna',
                            ],
                            [
                                'no' => 'A.2.3',
                                'nama_item' => 'Lebah madu',
                                'nama_latin' => 'Apis cerana',
                                'nama_daerah' => 'Tawon madu',
                                'total_nilai_ekonomi' => 324_300_000.00,
                                'kategori' => 'provisioning_fauna',
                            ],
                        ],
                    ],
                    [
                        'nama' => 'Regulating',
                        'kode' => 'B',
                        'subtotal' => 876_540_000.00,
                        'items' => [
                            [
                                'no' => 'B.1',
                                'nama_item' => 'Pencegah erosi permukaan',
                                'total_nilai_ekonomi' => 342_150_000.00,
                                'kategori' => 'regulating',
                            ],
                            [
                                'no' => 'B.2',
                                'nama_item' => 'Penyerap karbon',
                                'total_nilai_ekonomi' => 278_390_000.00,
                                'kategori' => 'regulating',
                            ],
                            [
                                'no' => 'B.3',
                                'nama_item' => 'Pengatur siklus air',
                                'total_nilai_ekonomi' => 256_000_000.00,
                                'kategori' => 'regulating',
                            ],
                        ],
                    ],
                    [
                        'nama' => 'Supporting',
                        'kode' => 'C',
                        'subtotal' => 534_260_000.00,
                        'items' => [
                            [
                                'no' => 'C.1',
                                'nama_item' => 'Habitat fauna kecil',
                                'total_nilai_ekonomi' => 287_360_000.00,
                                'kategori' => 'supporting',
                            ],
                            [
                                'no' => 'C.2',
                                'nama_item' => 'Siklus nutrien',
                                'total_nilai_ekonomi' => 246_900_000.00,
                                'kategori' => 'supporting',
                            ],
                        ],
                    ],
                    [
                        'nama' => 'Cultural',
                        'kode' => 'D',
                        'subtotal' => 387_650_000.00,
                        'items' => [
                            [
                                'no' => 'D.1',
                                'nama_item' => 'Nilai estetika lanskap',
                                'total_nilai_ekonomi' => 198_250_000.00,
                                'kategori' => 'cultural',
                            ],
                            [
                                'no' => 'D.2',
                                'nama_item' => 'Wisata alam terbuka',
                                'total_nilai_ekonomi' => 189_400_000.00,
                                'kategori' => 'cultural',
                            ],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // 4. BELUKAR
            // ============================================================
            [
                'nama' => 'Belukar',
                'kategori' => [
                    [
                        'nama' => 'Provisioning',
                        'kode' => 'A',
                        'subtotal' => 897_620_000.00,
                        'items' => [
                            // A.1 Flora
                            [
                                'no' => 'A.1.1',
                                'nama_item' => 'Alang-alang',
                                'nama_latin' => 'Imperata cylindrica',
                                'nama_daerah' => 'Eurih',
                                'total_nilai_ekonomi' => 112_400_000.00,
                                'kategori' => 'provisioning_flora',
                            ],
                            [
                                'no' => 'A.1.2',
                                'nama_item' => 'Perdu liar',
                                'nama_latin' => 'Lantana camara',
                                'nama_daerah' => 'Tembelekan',
                                'total_nilai_ekonomi' => 67_820_000.00,
                                'kategori' => 'provisioning_flora',
                            ],
                            [
                                'no' => 'A.1.3',
                                'nama_item' => 'Rumput gajah',
                                'nama_latin' => 'Pennisetum purpureum',
                                'nama_daerah' => 'Rumput napier',
                                'total_nilai_ekonomi' => 145_300_000.00,
                                'kategori' => 'provisioning_flora',
                            ],
                            // A.2 Fauna
                            [
                                'no' => 'A.2.1',
                                'nama_item' => 'Babi hutan',
                                'nama_latin' => 'Sus scrofa',
                                'nama_daerah' => 'Celeng',
                                'total_nilai_ekonomi' => 198_700_000.00,
                                'kategori' => 'provisioning_fauna',
                            ],
                            [
                                'no' => 'A.2.2',
                                'nama_item' => 'Musang luwak',
                                'nama_latin' => 'Paradoxurus hermaphroditus',
                                'nama_daerah' => 'Luwak',
                                'total_nilai_ekonomi' => 178_200_000.00,
                                'kategori' => 'provisioning_fauna',
                            ],
                            [
                                'no' => 'A.2.3',
                                'nama_item' => 'Lebah madu',
                                'nama_latin' => 'Apis cerana',
                                'nama_daerah' => 'Tawon madu',
                                'total_nilai_ekonomi' => 195_200_000.00,
                                'kategori' => 'provisioning_fauna',
                            ],
                        ],
                    ],
                    [
                        'nama' => 'Regulating',
                        'kode' => 'B',
                        'subtotal' => 567_430_000.00,
                        'items' => [
                            [
                                'no' => 'B.1',
                                'nama_item' => 'Pencegah erosi',
                                'total_nilai_ekonomi' => 213_450_000.00,
                                'kategori' => 'regulating',
                            ],
                            [
                                'no' => 'B.2',
                                'nama_item' => 'Penyerap karbon',
                                'total_nilai_ekonomi' => 178_680_000.00,
                                'kategori' => 'regulating',
                            ],
                            [
                                'no' => 'B.3',
                                'nama_item' => 'Pengendali hama alami',
                                'total_nilai_ekonomi' => 175_300_000.00,
                                'kategori' => 'regulating',
                            ],
                        ],
                    ],
                    [
                        'nama' => 'Supporting',
                        'kode' => 'C',
                        'subtotal' => 378_450_000.00,
                        'items' => [
                            [
                                'no' => 'C.1',
                                'nama_item' => 'Habitat fauna transisi',
                                'total_nilai_ekonomi' => 198_250_000.00,
                                'kategori' => 'supporting',
                            ],
                            [
                                'no' => 'C.2',
                                'nama_item' => 'Daur ulang nutrien',
                                'total_nilai_ekonomi' => 180_200_000.00,
                                'kategori' => 'supporting',
                            ],
                        ],
                    ],
                    [
                        'nama' => 'Cultural',
                        'kode' => 'D',
                        'subtotal' => 265_380_000.00,
                        'items' => [
                            [
                                'no' => 'D.1',
                                'nama_item' => 'Nilai estetika',
                                'total_nilai_ekonomi' => 132_180_000.00,
                                'kategori' => 'cultural',
                            ],
                            [
                                'no' => 'D.2',
                                'nama_item' => 'Rekreasi dan pendidikan',
                                'total_nilai_ekonomi' => 133_200_000.00,
                                'kategori' => 'cultural',
                            ],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // 5. LAHAN TERBANGUN
            // ============================================================
            [
                'nama' => 'Lahan Terbangun',
                'kategori' => [
                    [
                        'nama' => 'Provisioning',
                        'kode' => 'A',
                        'subtotal' => 534_720_000.00,
                        'items' => [
                            // A.1 Flora
                            [
                                'no' => 'A.1.1',
                                'nama_item' => 'Pohon pelindung jalan',
                                'nama_latin' => 'Samanea saman',
                                'nama_daerah' => 'Trembesi',
                                'total_nilai_ekonomi' => 87_350_000.00,
                                'kategori' => 'provisioning_flora',
                            ],
                            [
                                'no' => 'A.1.2',
                                'nama_item' => 'Tanaman hias perkotaan',
                                'nama_latin' => 'Tabebuia rosea',
                                'nama_daerah' => 'Tabebuya',
                                'total_nilai_ekonomi' => 65_470_000.00,
                                'kategori' => 'provisioning_flora',
                            ],
                            [
                                'no' => 'A.1.3',
                                'nama_item' => 'Rumput taman',
                                'nama_latin' => 'Axonopus compressus',
                                'nama_daerah' => 'Rumput paetan',
                                'total_nilai_ekonomi' => 42_100_000.00,
                                'kategori' => 'provisioning_flora',
                            ],
                            // A.2 Fauna
                            [
                                'no' => 'A.2.1',
                                'nama_item' => 'Burung gereja',
                                'nama_latin' => 'Passer montanus',
                                'nama_daerah' => 'Pipit',
                                'total_nilai_ekonomi' => 124_500_000.00,
                                'kategori' => 'provisioning_fauna',
                            ],
                            [
                                'no' => 'A.2.2',
                                'nama_item' => 'Tokek',
                                'nama_latin' => 'Gekko gecko',
                                'nama_daerah' => 'Tokek rumah',
                                'total_nilai_ekonomi' => 98_300_000.00,
                                'kategori' => 'provisioning_fauna',
                            ],
                            [
                                'no' => 'A.2.3',
                                'nama_item' => 'Walet',
                                'nama_latin' => 'Aerodramus fuciphagus',
                                'nama_daerah' => 'Burung walet',
                                'total_nilai_ekonomi' => 117_000_000.00,
                                'kategori' => 'provisioning_fauna',
                            ],
                        ],
                    ],
                    [
                        'nama' => 'Regulating',
                        'kode' => 'B',
                        'subtotal' => 423_580_000.00,
                        'items' => [
                            [
                                'no' => 'B.1',
                                'nama_item' => 'Penyerap polusi udara',
                                'total_nilai_ekonomi' => 165_280_000.00,
                                'kategori' => 'regulating',
                            ],
                            [
                                'no' => 'B.2',
                                'nama_item' => 'Pengatur suhu urban',
                                'total_nilai_ekonomi' => 142_300_000.00,
                                'kategori' => 'regulating',
                            ],
                            [
                                'no' => 'B.3',
                                'nama_item' => 'Pengelola limpasan air hujan',
                                'total_nilai_ekonomi' => 116_000_000.00,
                                'kategori' => 'regulating',
                            ],
                        ],
                    ],
                    [
                        'nama' => 'Supporting',
                        'kode' => 'C',
                        'subtotal' => 287_450_000.00,
                        'items' => [
                            [
                                'no' => 'C.1',
                                'nama_item' => 'Habitat fauna urban',
                                'total_nilai_ekonomi' => 156_750_000.00,
                                'kategori' => 'supporting',
                            ],
                            [
                                'no' => 'C.2',
                                'nama_item' => 'Ruang terbuka hijau',
                                'total_nilai_ekonomi' => 130_700_000.00,
                                'kategori' => 'supporting',
                            ],
                        ],
                    ],
                    [
                        'nama' => 'Cultural',
                        'kode' => 'D',
                        'subtotal' => 298_650_000.00,
                        'items' => [
                            [
                                'no' => 'D.1',
                                'nama_item' => 'Rekreasi taman kota',
                                'total_nilai_ekonomi' => 145_400_000.00,
                                'kategori' => 'cultural',
                            ],
                            [
                                'no' => 'D.2',
                                'nama_item' => 'Nilai estetika perkotaan',
                                'total_nilai_ekonomi' => 87_250_000.00,
                                'kategori' => 'cultural',
                            ],
                            [
                                'no' => 'D.3',
                                'nama_item' => 'Warisan budaya lokal',
                                'total_nilai_ekonomi' => 66_000_000.00,
                                'kategori' => 'cultural',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}

