<?php

namespace App\Http\Controllers\Api\V1\Valuation;

use App\Http\Controllers\Controller;
use App\Models\Proyek;
use App\Models\Role;
use App\Services\Valuation\EconomicValuationCalculator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectValuationController extends Controller
{
    public function __construct(
        private readonly EconomicValuationCalculator $calculator
    ) {}

    public function getResults(Request $request, Proyek $proyek): JsonResponse
    {
        $user = $request->user() ?? $request->user('sanctum') ?? auth('sanctum')->user();
        if ($user) {
            $roleName = $user->role ? Role::normalize($user->role->nama_role) : null;
            if ($roleName === Role::PENELITI && (int) $proyek->id_user !== (int) $user->id_user) {
                return response()->json(['message' => 'Anda tidak memiliki akses ke valuasi proyek ini.'], 403);
            }
        }

        $result = $this->calculator->calculateForProject($proyek);
        return response()->json(['data' => $result]);
    }
}
