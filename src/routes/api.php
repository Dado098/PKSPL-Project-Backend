<?php

// Controller API versi 1 untuk modul data utama aplikasi.
use App\Http\Controllers\Api\V1\AnalisisAiController;
use App\Http\Controllers\Api\V1\AreaTerdampakController;
use App\Http\Controllers\Api\V1\BasisDataAiController;
use App\Http\Controllers\Api\V1\CulturalServiceController;
use App\Http\Controllers\Api\V1\DatasetReferensiController;
use App\Http\Controllers\Api\V1\EkosistemController;
use App\Http\Controllers\Api\V1\HasilValuasiController;
use App\Http\Controllers\Api\V1\HistoriController;
use App\Http\Controllers\Api\V1\KabupatenKotaController;
use App\Http\Controllers\Api\V1\KecamatanController;
use App\Http\Controllers\Api\V1\MetodeValuasiController;
use App\Http\Controllers\Api\V1\ProyekController;
use App\Http\Controllers\Api\V1\ProyekDashboardController;
use App\Http\Controllers\Api\V1\IndexController;
use App\Http\Controllers\Api\V1\JenisTutupanLahanController;
use App\Http\Controllers\Api\V1\ProvisioningServiceController;
use App\Http\Controllers\Api\V1\ProvinsiController;
use App\Http\Controllers\Api\V1\RegulatingServiceController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\SupportingServiceController;
use App\Http\Controllers\Api\V1\TestController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\AIController;
use App\Http\Controllers\Api\V1\ValidasiAnalystController;
use Illuminate\Support\Facades\Route;

// ============================================================
// BAB 1. AKSES DAN ADMINISTRASI API V1
// Seluruh endpoint dalam bab ini menggunakan prefiks v1.
// ============================================================
Route::prefix('v1')->group(function (): void {
    // ------------------------------------------------------------
    // 1.1 AUTENTIKASI
    // Menangani pendaftaran, sesi pengguna, dan integrasi Google.
    // ------------------------------------------------------------
    // 1.1.1 Mengarahkan pengguna ke autentikasi Google.
    Route::get('auth/google/redirect', [AuthController::class, 'googleRedirect']);

    // 1.1.2 Menerima hasil autentikasi dari Google.
    Route::get('auth/google/callback', [AuthController::class, 'googleCallback']);

    // 1.1.3 Mendaftarkan akun pengguna baru.
    Route::post('auth/register', [AuthController::class, 'register']);

    // 1.1.4 Membuat sesi untuk pengguna yang berhasil masuk.
    Route::post('auth/login', [AuthController::class, 'login']);

    // 1.1.5 Endpoint sesi yang memerlukan autentikasi Sanctum.
    Route::middleware('auth:sanctum')->group(function (): void {
        // 1.1.5.1 Mengakhiri sesi pengguna terautentikasi.
        Route::post('auth/logout', [AuthController::class, 'logout']);

        // 1.1.5.2 Menampilkan profil pengguna terautentikasi.
        Route::get('auth/me', [AuthController::class, 'me']);
    });

    // ------------------------------------------------------------
    // 1.2 PENGUJIAN AKSES BERDASARKAN PERAN
    // Prefiks ini dilindungi Sanctum dan memvalidasi peran pengguna.
    // ------------------------------------------------------------
    Route::prefix('test')->middleware(['auth:sanctum'])->group(function (): void {
        // 1.2.1 Memverifikasi akses untuk peran Administrator.
        Route::get('admin', [TestController::class, 'admin'])->middleware('role:Administrator');

        // 1.2.2 Memverifikasi akses untuk peran Analyst.
        Route::get('analyst', [TestController::class, 'analyst'])->middleware('role:Analyst');

        // 1.2.3 Memverifikasi akses untuk peran Peneliti.
        Route::get('peneliti', [TestController::class, 'peneliti'])->middleware('role:Peneliti');

        // 1.2.4 Memverifikasi akses untuk peran Guest.
        Route::get('guest', [TestController::class, 'guest'])->middleware('role:Guest');
    });

    // ------------------------------------------------------------
    // 1.3 MANAJEMEN PERAN
    // Menyediakan CRUD peran dengan nama yang telah disepakati.
    // ------------------------------------------------------------

    // 1.3.1 Mengelola daftar, detail, pembuatan, perubahan, dan penghapusan peran.
    Route::apiResource('roles', RoleController::class);

    // ------------------------------------------------------------
    // 1.4 MANAJEMEN PENGGUNA
    // Mengelola akun pengguna aplikasi.
    // ------------------------------------------------------------
    // 1.4.1 Mengelola daftar, detail, pembuatan, perubahan, dan penghapusan pengguna.
    Route::middleware(['auth:sanctum', 'role:Admin'])->group(function (): void {
        Route::apiResource('users', UserController::class);
    });

    // ============================================================
    // BAB 2. WILAYAH ADMINISTRATIF
    // Menyediakan master wilayah untuk kebutuhan data analisis.
    // ============================================================

    // ------------------------------------------------------------
    // 2.1 PROVINSI
    // ------------------------------------------------------------
    // 2.1.1 Mengelola CRUD master provinsi.
    Route::apiResource('provinsi', ProvinsiController::class);

    // ------------------------------------------------------------
    // 2.2 KABUPATEN/KOTA
    // ------------------------------------------------------------
    // 2.2.1 Mengelola CRUD master kabupaten/kota dengan binding kabupatenKota.
    Route::apiResource('kabupaten-kota', KabupatenKotaController::class)->parameters(['kabupaten-kota' => 'kabupatenKota']);

    // ------------------------------------------------------------
    // 2.3 KECAMATAN
    // ------------------------------------------------------------
    // 2.3.1 Mengelola CRUD master kecamatan.
    Route::apiResource('kecamatan', KecamatanController::class);

    // ============================================================
    // BAB 3. PROYEK DAN DATA ANALISIS
    // Mendukung pengelolaan proyek serta data dasar analisis valuasi.
    // ============================================================

    // ------------------------------------------------------------
    // 3.1 PROYEK
    // ------------------------------------------------------------
    // 3.1.1 Mengelola CRUD proyek analisis valuasi.
    Route::apiResource('proyek', ProyekController::class);

    // ------------------------------------------------------------
    // 3.2 DASHBOARD PROYEK
    // ------------------------------------------------------------
    // 3.2.1 Menyajikan ringkasan untuk proyek pada parameter {proyek}.
    Route::get('proyek/{proyek}/dashboard', ProyekDashboardController::class);

    // ------------------------------------------------------------
    // 3.3 INDEKS
    // ------------------------------------------------------------
    // 3.3.1 Mengelola CRUD nilai indeks yang digunakan dalam analisis.
    Route::apiResource('indexes', IndexController::class);

    // ------------------------------------------------------------
    // 3.4 JENIS TUTUPAN LAHAN
    // ------------------------------------------------------------
    // 3.4.1 Mengelola CRUD dengan binding model jenisTutupanLahan.
    Route::apiResource('jenis-tutupan-lahan', JenisTutupanLahanController::class)->parameters(['jenis-tutupan-lahan' => 'jenisTutupanLahan']);

    // ------------------------------------------------------------
    // 3.5 AREA TERDAMPAK
    // ------------------------------------------------------------
    // 3.5.1 Mengelola CRUD area terdampak dengan binding areaTerdampak.
    Route::apiResource('area-terdampak', AreaTerdampakController::class)->parameters(['area-terdampak' => 'areaTerdampak']);

    // ============================================================
    // BAB 4. EKOSISTEM DAN JASA EKOSISTEM
    // Mengelola ekosistem serta komponen jasa ekosistem.
    // ============================================================

    // ------------------------------------------------------------
    // 4.1 EKOSISTEM
    // ------------------------------------------------------------
    // 4.1.1 Mengelola CRUD master ekosistem.
    Route::apiResource('ekosistem', EkosistemController::class);

    // ------------------------------------------------------------
    // 4.2 JASA PENYEDIAAN
    // ------------------------------------------------------------
    // 4.2.1 Mengelola CRUD dengan binding provisioningService.
    Route::apiResource('provisioning-services', ProvisioningServiceController::class)->parameters(['provisioning-services' => 'provisioningService']);

    // ------------------------------------------------------------
    // 4.3 JASA PENGATURAN
    // ------------------------------------------------------------
    // 4.3.1 Mengelola CRUD dengan binding regulatingService.
    Route::apiResource('regulating-services', RegulatingServiceController::class)->parameters(['regulating-services' => 'regulatingService']);

    // ------------------------------------------------------------
    // 4.4 JASA PENDUKUNG
    // ------------------------------------------------------------
    // 4.4.1 Mengelola CRUD dengan binding supportingService.
    Route::apiResource('supporting-services', SupportingServiceController::class)->parameters(['supporting-services' => 'supportingService']);

    // ------------------------------------------------------------
    // 4.5 JASA BUDAYA
    // ------------------------------------------------------------
    // 4.5.1 Mengelola CRUD dengan binding culturalService.
    Route::apiResource('cultural-services', CulturalServiceController::class)->parameters(['cultural-services' => 'culturalService']);

    // ============================================================
    // BAB 5. VALUASI
    // Menyediakan konfigurasi metode dan hasil valuasi.
    // ============================================================

    // ------------------------------------------------------------
    // 5.1 METODE VALUASI
    // ------------------------------------------------------------
    // 5.1.1 Mengelola CRUD dengan binding metodeValuasi.
    Route::apiResource('metode-valuasi', MetodeValuasiController::class)->parameters(['metode-valuasi' => 'metodeValuasi']);

    // ------------------------------------------------------------
    // 5.2 HASIL VALUASI
    // ------------------------------------------------------------
    // 5.2.1 Mengelola CRUD dengan binding hasilValuasi.
    Route::apiResource('hasil-valuasi', HasilValuasiController::class)->parameters(['hasil-valuasi' => 'hasilValuasi']);

    // ============================================================
    // BAB 6. KECERDASAN BUATAN DAN VALIDASI
    // Menyediakan data referensi, proses AI, dan hasil validasi analis.
    // ============================================================

    // ------------------------------------------------------------
    // 6.1 DATASET REFERENSI
    // ------------------------------------------------------------
    // 6.1.1 Mengelola CRUD dengan binding datasetReferensi.
    Route::apiResource('dataset-referensi', DatasetReferensiController::class)->parameters(['dataset-referensi' => 'datasetReferensi']);

    // ------------------------------------------------------------
    // 6.2 BASIS DATA AI
    // ------------------------------------------------------------
    // 6.2.1 Mengelola CRUD dengan binding basisDataAi.
    Route::apiResource('basis-data-ai', BasisDataAiController::class)->parameters(['basis-data-ai' => 'basisDataAi']);

    // ------------------------------------------------------------
    // 6.3 ANALISIS AI
    // ------------------------------------------------------------
    // 6.3.1 Menyediakan daftar, pembuatan, dan detail dengan binding analisisAi.
    Route::apiResource('analisis-ai', AnalisisAiController::class)->only(['index', 'store', 'show'])->parameters(['analisis-ai' => 'analisisAi']);

    // ------------------------------------------------------------
    // 6.4 HISTORI ANALISIS
    // ------------------------------------------------------------
    // 6.4.1 Menyediakan daftar, pembuatan, dan detail histori analisis.
    Route::apiResource('histori', HistoriController::class)->only(['index', 'store', 'show']);

    // ------------------------------------------------------------
    // 6.5 VALIDASI ANALYST
    // ------------------------------------------------------------
    // 6.5.1 Mengelola CRUD dengan binding validasiAnalyst.
    Route::apiResource('validasi-analyst', ValidasiAnalystController::class)->parameters(['validasi-analyst' => 'validasiAnalyst']);

    // ------------------------------------------------------------
    // 6.6 LAYANAN OPERASIONAL AI
    // Hanya pengguna dengan peran Admin, Analyst, atau Peneliti yang dapat mengaksesnya.
    // ------------------------------------------------------------
    Route::middleware(['auth:sanctum','role:Admin,Analyst,Peneliti'])
        ->prefix('ai')
        ->group(function (): void {
            // 6.6.1 Menguji ketersediaan integrasi AI.
            Route::get('/test', [AIController::class, 'test']);

            // 6.6.2 Memeriksa kesehatan layanan AI.
            Route::post('/health', [AIController::class, 'health']);

            // 6.6.3 Memproses permintaan pembuatan respons AI.
            Route::post('/generate', [AIController::class, 'generate']);
        });
});

// BAB 7 didefinisikan dalam berkas terpisah untuk modul review dan diskusi.
require __DIR__ . '/api_review.php';

// BAB 8 didefinisikan dalam berkas terpisah untuk modul valuasi ekonomi terintegrasi.
require __DIR__ . '/api_valuation.php';
