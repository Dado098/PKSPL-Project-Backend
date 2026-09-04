<?php

namespace Database\Seeders;

use App\Models\AdministrativeBoundary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class AdministrativeBoundarySeeder extends Seeder
{
    /**
     * Run the administrative boundaries database seeds.
     * Idempotent & safe: only runs import if boundaries are not fully populated.
     */
    public function run(): void
    {
        $level3Count = AdministrativeBoundary::where('level', 3)->count();

        // If level 3 (kecamatan) boundaries are already populated (>= 6000 rows), skip import for speed
        if ($level3Count >= 6000) {
            $this->command->info("Administrative boundaries already populated ({$level3Count} kecamatan). Skipping re-import.");
            return;
        }

        $this->command->info('Seeding administrative boundaries (Levels 1, 2, 3)...');
        Artisan::call('boundaries:import', [
            '--level' => [1, 2, 3],
        ]);
        $this->command->info(Artisan::output());
    }
}
