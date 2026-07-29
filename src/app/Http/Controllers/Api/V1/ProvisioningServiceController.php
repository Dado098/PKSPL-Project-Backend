<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ProvisioningServiceRequest;
use App\Http\Resources\ProvisioningServiceResource;
use App\Models\ProvisioningService;
use Illuminate\Http\Request;

class ProvisioningServiceController extends ApiResourceController
{
    protected string $model = ProvisioningService::class;
    protected string $resource = ProvisioningServiceResource::class;

    public function index(Request $request) { return $this->indexResource($request); }
    public function store(ProvisioningServiceRequest $request) { return $this->storeResource($request->validated()); }
    public function show(ProvisioningService $provisioningService) { return $this->showResource($provisioningService); }
    public function update(ProvisioningServiceRequest $request, ProvisioningService $provisioningService) { return $this->updateResource($provisioningService, $request->validated()); }
    public function destroy(ProvisioningService $provisioningService) { return $this->destroyResource($provisioningService); }
}
