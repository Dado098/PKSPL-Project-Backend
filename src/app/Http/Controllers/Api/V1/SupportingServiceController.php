<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\SupportingServiceRequest;
use App\Http\Resources\SupportingServiceResource;
use App\Models\SupportingService;
use Illuminate\Http\Request;

/** Menangani endpoint CRUD jasa pendukung. */
class SupportingServiceController extends ApiResourceController
{
    protected string $model = SupportingService::class;
    protected string $resource = SupportingServiceResource::class;

    // Meneruskan operasi CRUD ke helper dengan request yang sudah tervalidasi.
    public function index(Request $request) { return $this->indexResource($request); }
    public function store(SupportingServiceRequest $request) { $payload = $request->validated(); $payload['kategori_tev'] = $payload['kategori_tev'] ?? 'OV'; return $this->storeResource($payload); }
    public function show(SupportingService $supportingService) { return $this->showResource($supportingService); }
    public function update(SupportingServiceRequest $request, SupportingService $supportingService) { $payload = $request->validated(); $payload['kategori_tev'] = $payload['kategori_tev'] ?? 'OV'; return $this->updateResource($supportingService, $payload); }
    public function destroy(SupportingService $supportingService) { return $this->destroyResource($supportingService); }
}
