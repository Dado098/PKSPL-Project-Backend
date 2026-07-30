<?php

use App\Http\Controllers\Api\V1\AnalisisAiController;
use App\Http\Controllers\Api\V1\AreaTerdampakController;
use App\Http\Controllers\Api\V1\BasisDataAiController;
use App\Http\Controllers\Api\V1\CulturalServiceController;
use App\Http\Controllers\Api\V1\DatasetReferensiController;
use App\Http\Controllers\Api\V1\EkosistemController;
use App\Http\Controllers\Api\V1\HasilValuasiController;
use App\Http\Controllers\Api\V1\HistoriController;
use App\Http\Controllers\Api\V1\MetodeValuasiController;
use App\Http\Controllers\Api\V1\ProyekController;
use App\Http\Controllers\Api\V1\ProvisioningServiceController;
use App\Http\Controllers\Api\V1\RegulatingServiceController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\SupportingServiceController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ValidasiAnalystController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    // CRUD role dibatasi oleh RoleRequest pada empat nama role yang disepakati.
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('proyek', ProyekController::class);
    Route::apiResource('ekosistem', EkosistemController::class);
    Route::apiResource('area-terdampak', AreaTerdampakController::class)->parameters(['area-terdampak' => 'areaTerdampak']);
    Route::apiResource('provisioning-services', ProvisioningServiceController::class)->parameters(['provisioning-services' => 'provisioningService']);
    Route::apiResource('regulating-services', RegulatingServiceController::class)->parameters(['regulating-services' => 'regulatingService']);
    Route::apiResource('supporting-services', SupportingServiceController::class)->parameters(['supporting-services' => 'supportingService']);
    Route::apiResource('cultural-services', CulturalServiceController::class)->parameters(['cultural-services' => 'culturalService']);
    Route::apiResource('metode-valuasi', MetodeValuasiController::class)->parameters(['metode-valuasi' => 'metodeValuasi']);
    Route::apiResource('hasil-valuasi', HasilValuasiController::class)->parameters(['hasil-valuasi' => 'hasilValuasi']);
    Route::apiResource('dataset-referensi', DatasetReferensiController::class)->parameters(['dataset-referensi' => 'datasetReferensi']);
    Route::apiResource('basis-data-ai', BasisDataAiController::class)->parameters(['basis-data-ai' => 'basisDataAi']);
    Route::apiResource('analisis-ai', AnalisisAiController::class)->only(['index', 'store', 'show'])->parameters(['analisis-ai' => 'analisisAi']);
    Route::apiResource('histori', HistoriController::class)->only(['index', 'store', 'show']);
    Route::apiResource('validasi-analyst', ValidasiAnalystController::class)->parameters(['validasi-analyst' => 'validasiAnalyst']);
});
