<?php

namespace Database\Seeders;

use App\Models\Proyek;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ValuationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Ambil proyek pertama yang ada dari ProyekSeeder
        $proyek = Proyek::first();

        if (!$proyek) {
            $this->command->warn('Tidak ada data Proyek. ValuationSeeder dilewati.');
            return;
        }

        $idProyek = $proyek->id_proyek;

        // 1. Project Valuation Settings
        DB::table('project_valuation_settings')->insert([
            'id_proyek' => $idProyek,
            'base_year' => 2024,
            'discount_rate' => 0.0500,
            'currency' => 'IDR',
            'analysis_period' => 10,
            'eop_value_basis' => 'net',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 2. Valuation Modules
        $moduleIdTcm = DB::table('valuation_modules')->insertGetId([
            'id_proyek' => $idProyek,
            'module_type' => 'tcm',
            'name' => 'TCM Pantai Indah',
            'description' => 'Analisis Travel Cost Method untuk Pantai',
            'created_at' => $now,
            'updated_at' => $now,
        ], 'id_module');

        $moduleIdEop = DB::table('valuation_modules')->insertGetId([
            'id_proyek' => $idProyek,
            'module_type' => 'eop',
            'name' => 'EOP Ikan Tangkap',
            'description' => 'Analisis Effect on Production untuk nelayan lokal',
            'created_at' => $now,
            'updated_at' => $now,
        ], 'id_module');

        // 3. EOP Data
        DB::table('eop_data')->insert([
            'id_proyek' => $idProyek,
            'id_module' => $moduleIdEop,
            'commodity' => 'Ikan Tongkol',
            'quantity_before' => 1000.00,
            'quantity_after' => 1200.00,
            'output_price' => 25000.00,
            'production_cost' => 5000000.00,
            'net_value' => 25000000.00,
            'estimation_method' => 'Market Price',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 4. TCM Data
        DB::table('tcm_data')->insert([
            'id_proyek' => $idProyek,
            'id_module' => $moduleIdTcm,
            'respondent_id' => 'R-001',
            'distance' => 15.5,
            'total_travel_cost' => 50000.00,
            'annual_visits' => 4,
            'time_cost' => 25000.00,
            'consumer_surplus' => 125000.00,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 5. TCM Analysis
        DB::table('tcm_analyses')->insert([
            'id_proyek' => $idProyek,
            'id_module' => $moduleIdTcm,
            'model_type' => 'poisson',
            'dependent_variable' => 'annual_visits',
            'consumer_surplus_per_visit' => 31250.00,
            'total_recreation_value' => 156250000.00,
            'r_squared' => 0.8500,
            'n' => 100,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 6. Benefits
        DB::table('benefits')->insert([
            [
                'id_proyek' => $idProyek,
                'category' => 'direct_use',
                'subcategory' => 'production',
                'ecosystem_service_group' => 'provisioning',
                'value' => 25000000.00,
                'period_year' => null,
                'pv_value' => null,
                'data_source' => 'EOP Ikan Tangkap',
                'source_module' => 'eop',
                'source_record_id' => null,
                'description' => 'Nilai tambah produksi perikanan',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_proyek' => $idProyek,
                'category' => 'direct_use',
                'subcategory' => 'recreation',
                'ecosystem_service_group' => 'cultural',
                'value' => 156250000.00,
                'period_year' => null,
                'pv_value' => null,
                'data_source' => 'TCM Pantai Indah',
                'source_module' => 'tcm',
                'source_record_id' => null,
                'description' => 'Nilai rekreasi tahunan',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);

        // 7. Costs
        DB::table('costs')->insert([
            'id_proyek' => $idProyek,
            'category' => 'direct',
            'subcategory' => 'maintenance',
            'activity_group' => 'operasional',
            'value' => 10000000.00,
            'year_applied' => null,
            'pv_value' => null,
            'description' => 'Biaya operasional tahunan pengawasan ekosistem',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        
        $this->command->info('Data valuasi berhasil dibuat (ValuationSeeder).');
    }
}
