<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Proyek;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Menyajikan statistik agregat Landing Page secara real-time dari database.
 */
class StatisticsController extends Controller
{
    public function index(): JsonResponse
    {
        // 1. Total Projects / Analisis
        $totalProjects = Proyek::query()->count();

        // 2. Total Peneliti (User dengan role 'Peneliti')
        $totalResearchers = User::query()
            ->whereHas('role', function ($query) {
                $query->where('nama_role', 'Peneliti');
            })
            ->count();

        // 3. Status Project Breakdown
        $rawStatuses = Proyek::query()
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $projectStatuses = array_merge([
            'Draft' => 0,
            'Proses' => 0,
            'Selesai' => 0,
            'Dibatalkan' => 0,
        ], $rawStatuses);

        // 4. Grafik Per Bulan (Tahun Berjalan)
        $currentYear = (int) date('Y');
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyProjects = [];

        for ($m = 1; $m <= 12; $m++) {
            $count = Proyek::query()
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $m)
                ->count();

            $label = $monthNames[$m - 1];
            $monthlyProjects[] = [
                'month' => $label,
                'name' => $label,
                'count' => $count,
                'projects' => $count,
                'users' => $count,
            ];
        }

        // 5. Sebaran Ekosistem
        $ecosystemData = DB::table('area_terdampak')
            ->join('ekosistem', 'area_terdampak.id_ekosistem', '=', 'ekosistem.id_ekosistem')
            ->select('ekosistem.nama_ekosistem as name', DB::raw('count(*) as count'))
            ->groupBy('ekosistem.nama_ekosistem')
            ->get();

        if ($ecosystemData->isEmpty()) {
            $ecosystemData = DB::table('ekosistem')
                ->select('nama_ekosistem as name', DB::raw('0 as count'))
                ->get();
        }

        return response()->json([
            'total_projects' => $totalProjects,
            'total_researchers' => $totalResearchers,
            'project_statuses' => $projectStatuses,
            'monthly_projects' => $monthlyProjects,
            'ecosystem_distribution' => $ecosystemData,
        ]);
    }
}
