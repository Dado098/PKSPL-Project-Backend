<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ProvisioningServiceRequest;
use App\Http\Resources\ProvisioningServiceResource;
use App\Models\ProvisioningService;
use Illuminate\Http\Request;

/** Menangani endpoint CRUD jasa penyediaan. */
class ProvisioningServiceController extends ApiResourceController
{
    protected string $model = ProvisioningService::class;
    protected string $resource = ProvisioningServiceResource::class;

    // Meneruskan operasi CRUD ke helper dengan request yang sudah tervalidasi.
    public function index(Request $request) { return $this->indexResource($request); }
    public function store(ProvisioningServiceRequest $request) { $payload = $request->validated(); $payload['kategori_tev'] = $payload['kategori_tev'] ?? 'DUV'; return $this->storeResource($payload); }
    public function show(ProvisioningService $provisioningService) { return $this->showResource($provisioningService); }
    public function update(ProvisioningServiceRequest $request, ProvisioningService $provisioningService) { $payload = $request->validated(); $payload['kategori_tev'] = $payload['kategori_tev'] ?? 'DUV'; return $this->updateResource($provisioningService, $payload); }
    public function destroy(ProvisioningService $provisioningService) { return $this->destroyResource($provisioningService); }
}
