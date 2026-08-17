<?php

namespace App\Http\Controllers\Api\V1\Valuation;

use App\Http\Controllers\Controller;
use App\Models\Proyek;
use App\Services\Valuation\EconomicValuationCalculator;
use Illuminate\Http\JsonResponse;

class ProjectValuationController extends Controller
{
    public function __construct(
        private readonly EconomicValuationCalculator $calculator
    ) {}

    public function getResults(Proyek $proyek): JsonResponse
    {
        $result = $this->calculator->calculateForProject($proyek);
        return response()->json(['data' => $result]);
    }
}
