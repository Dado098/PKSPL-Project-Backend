<?php

use App\Http\Controllers\Api\V1\Valuation\BenefitController;
use App\Http\Controllers\Api\V1\Valuation\CostController;
use App\Http\Controllers\Api\V1\Valuation\ProjectValuationController;
use App\Http\Controllers\Api\V1\Valuation\ValuationModuleController;
use Illuminate\Support\Facades\Route;

// ============================================================
// BAB 8. VALUASI EKONOMI TERINTEGRASI
// ============================================================
Route::prefix('v1')->group(function (): void {
    
    // 8.1 MANAJEMEN MODUL VALUASI
    Route::apiResource('valuation-modules', ValuationModuleController::class);

    // 8.2 MANAJEMEN BENEFIT
    Route::apiResource('benefits', BenefitController::class);

    // 8.3 MANAJEMEN COST
    Route::apiResource('costs', CostController::class);

    // 8.4 HASIL KALKULASI PROYEK (NPV, BCR, dll)
    Route::get('proyek/{proyek}/economic-valuation', [ProjectValuationController::class, 'getResults']);
    
});
