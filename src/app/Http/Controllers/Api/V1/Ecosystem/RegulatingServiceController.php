<?php

namespace App\Http\Controllers\Api\V1\Ecosystem;

use App\Http\Controllers\Api\V1\ApiResourceController;

use App\Http\Requests\Ecosystem\RegulatingServiceRequest;
use App\Http\Resources\Ecosystem\RegulatingServiceResource;
use App\Models\RegulatingService;
use Illuminate\Http\Request;

/** Menangani endpoint CRUD jasa pengaturan. */
class RegulatingServiceController extends ApiResourceController
{
    protected string $model = RegulatingService::class;
    protected string $resource = RegulatingServiceResource::class;

    // Meneruskan operasi CRUD ke helper dengan request yang sudah tervalidasi.
    public function index(Request $request) { return $this->indexResource($request); }
    public function store(RegulatingServiceRequest $request) { $payload = $request->validated(); $payload['kategori_tev'] = $payload['kategori_tev'] ?? 'IUV'; return $this->storeResource($payload); }
    public function show(RegulatingService $regulatingService) { return $this->showResource($regulatingService); }
    public function update(RegulatingServiceRequest $request, RegulatingService $regulatingService) { $payload = $request->validated(); $payload['kategori_tev'] = $payload['kategori_tev'] ?? 'IUV'; return $this->updateResource($regulatingService, $payload); }
    public function destroy(RegulatingService $regulatingService) { return $this->destroyResource($regulatingService); }
}
