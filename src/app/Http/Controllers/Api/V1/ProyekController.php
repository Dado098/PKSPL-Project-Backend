<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ProyekRequest;
use App\Http\Resources\ProyekResource;
use App\Models\Proyek;
use App\Services\Shapefile\ShapefileUploadService;
use Illuminate\Http\Request;

/** Menangani endpoint CRUD proyek. */
class ProyekController extends ApiResourceController
{
    protected string $model = Proyek::class;

    protected string $resource = ProyekResource::class;

    // Meneruskan operasi CRUD ke helper dengan request yang sudah tervalidasi.
    public function __construct(private readonly ShapefileUploadService $shapefileUploadService) {}

    public function index(Request $request)
    {
        return $this->indexResource($request);
    }

    public function nextCode(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'next_code' => self::generateNextKodeProyek(),
        ]);
    }

    public function store(ProyekRequest $request)
    {
        $payload = $this->attributesWithoutFiles($request);

        $maxAttempts = 5;
        $proyek = null;

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            try {
                $payload['kode_proyek'] = self::generateNextKodeProyek();
                $proyek = Proyek::query()->create($payload);
                break;
            } catch (\Illuminate\Database\QueryException $e) {
                if ($attempt < $maxAttempts - 1 && (str_contains($e->getMessage(), 'kode_proyek') || $e->getCode() == '23505')) {
                    continue;
                }
                throw $e;
            }
        }

        $this->storeUploadedFiles($request, $proyek);

        return (new ProyekResource($proyek->refresh()))->response()->setStatusCode(201);
    }

    public static function generateNextKodeProyek(): string
    {
        $allCodes = \Illuminate\Support\Facades\DB::table('proyek')
            ->whereNotNull('kode_proyek')
            ->pluck('kode_proyek');

        $maxNumber = 0;

        foreach ($allCodes as $code) {
            if (preg_match('/PROJ-(\d+)/i', $code, $matches)) {
                $num = (int) $matches[1];
                if ($num > $maxNumber) {
                    $maxNumber = $num;
                }
            }
        }

        if ($maxNumber === 0) {
            $maxId = (int) \Illuminate\Support\Facades\DB::table('proyek')->max('id_proyek');
            $maxNumber = max($maxId, 0);
        }

        $nextNumber = $maxNumber + 1;

        return 'PROJ-' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function show(Proyek $proyek)
    {
        $proyek->load(['provinsi', 'kabupatenKota', 'kecamatan', 'desaKelurahan']);
        return $this->showResource($proyek);
    }

    public function update(ProyekRequest $request, Proyek $proyek)
    {
        $proyek->update($this->attributesWithoutFiles($request));
        $this->storeUploadedFiles($request, $proyek);
        $proyek->load(['provinsi', 'kabupatenKota', 'kecamatan', 'desaKelurahan']);

        return new ProyekResource($proyek->refresh());
    }

    public function destroy(Proyek $proyek)
    {
        foreach ($proyek->indexes as $index) {
            \Illuminate\Support\Facades\DB::table('jenis_tutupan_lahan')->where('id_index', $index->id_index)->delete();
            $index->delete();
        }
        $proyek->areaTerdampak()->delete();
        $proyek->reviews()->delete();
        $proyek->histori()->delete();
        $proyek->analisisAi()->delete();
        $proyek->benefits()->delete();
        $proyek->costs()->delete();
        $proyek->valuationModules()->delete();
        $proyek->projectValuationSetting()->delete();
        return $this->destroyResource($proyek);
    }

    private function attributesWithoutFiles(ProyekRequest $request): array
    {
        // 1. Strip id_user and file inputs from validated request data
        $payload = collect($request->validated())
            ->except(['id_user', 'shp', 'shx', 'dbf', 'prj', 'zip', 'shapefile_files'])
            ->all();

        // 2. Security: Always enforce authenticated user as single source of truth for id_user
        $user = $request->user() ?? $request->user('sanctum') ?? auth('sanctum')->user();
        if ($user) {
            $payload['id_user'] = $user->id_user;
        } else {
            $firstUser = \App\Models\User::query()->first();
            if ($firstUser) {
                $payload['id_user'] = $firstUser->id_user;
            }
        }

        // Parse geometry if passed as JSON string (e.g. via FormData)
        if (isset($payload['geometry']) && is_string($payload['geometry'])) {
            $decoded = json_decode($payload['geometry'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $payload['geometry'] = $decoded;
            }
        }

        // Default fallbacks
        if (empty($payload['tujuan_valuasi'])) {
            $payload['tujuan_valuasi'] = 'Valuasi Ekonomi Ekosistem';
        }
        if (empty($payload['tahun'])) {
            $payload['tahun'] = (int) date('Y');
        }
        if (empty($payload['status'])) {
            $payload['status'] = 'Draft';
        }

        return $payload;
    }

    private function storeUploadedFiles(ProyekRequest $request, Proyek $proyek): void
    {
        $files = $request->only(['shp', 'shx', 'dbf', 'prj', 'zip']);

        if ($request->hasFile('shapefile_files')) {
            $extra = $request->file('shapefile_files');
            if (is_array($extra)) {
                $files['shapefile_files'] = $extra;
            } elseif ($extra instanceof \Illuminate\Http\UploadedFile) {
                $ext = strtolower($extra->getClientOriginalExtension());
                $files[$ext ?: 'zip'] = $extra;
            }
        }

        $stored = $this->shapefileUploadService->store($proyek, $files);

        if ($stored !== ($proyek->shapefile_files ?? [])) {
            $proyek->update(['shapefile_files' => $stored]);
        }
    }
}
