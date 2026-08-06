<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\ProyekResource;
use App\Models\Proyek;
use App\Services\Valuation\ProjectDashboardService;
use Illuminate\Http\JsonResponse;

class ProyekDashboardController
{
    public function __invoke(Proyek $proyek, ProjectDashboardService $dashboardService): JsonResponse
    {
        $dashboard = $dashboardService->build($proyek);

        return response()->json([
            'proyek' => new ProyekResource($dashboard['proyek']),
            'statistik' => $dashboard['statistik'],
            'indexes' => $dashboard['indexes'],
            'nilai' => $dashboard['nilai'],
        ]);
    }
}
