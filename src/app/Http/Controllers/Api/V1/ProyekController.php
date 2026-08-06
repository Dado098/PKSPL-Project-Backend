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

    public function store(ProyekRequest $request)
    {
        $payload = $this->attributesWithoutFiles($request);
        $proyek = Proyek::query()->create($payload);
        $this->storeUploadedFiles($request, $proyek);

        return (new ProyekResource($proyek->refresh()))->response()->setStatusCode(201);
    }

    public function show(Proyek $proyek)
    {
        return $this->showResource($proyek);
    }

    public function update(ProyekRequest $request, Proyek $proyek)
    {
        $proyek->update($this->attributesWithoutFiles($request));
        $this->storeUploadedFiles($request, $proyek);

        return new ProyekResource($proyek->refresh());
    }

    public function destroy(Proyek $proyek)
    {
        return $this->destroyResource($proyek);
    }

    private function attributesWithoutFiles(ProyekRequest $request): array
    {
        return collect($request->validated())->except(['shp', 'shx', 'dbf', 'prj', 'zip'])->all();
    }

    private function storeUploadedFiles(ProyekRequest $request, Proyek $proyek): void
    {
        $files = $request->only(['shp', 'shx', 'dbf', 'prj', 'zip']);
        $stored = $this->shapefileUploadService->store($proyek, $files);

        if ($stored !== ($proyek->shapefile_files ?? [])) {
            $proyek->update(['shapefile_files' => $stored]);
        }
    }
}
