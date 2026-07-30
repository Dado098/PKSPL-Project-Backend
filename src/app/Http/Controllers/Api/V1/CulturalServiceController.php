<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\CulturalServiceRequest;
use App\Http\Resources\CulturalServiceResource;
use App\Models\CulturalService;
use Illuminate\Http\Request;

/** Menangani endpoint CRUD jasa budaya. */
class CulturalServiceController extends ApiResourceController
{
    protected string $model = CulturalService::class;
    protected string $resource = CulturalServiceResource::class;

    // Meneruskan operasi CRUD ke helper dengan request yang sudah tervalidasi.
    public function index(Request $request) { return $this->indexResource($request); }
    public function store(CulturalServiceRequest $request) { return $this->storeResource($request->validated()); }
    public function show(CulturalService $culturalService) { return $this->showResource($culturalService); }
    public function update(CulturalServiceRequest $request, CulturalService $culturalService) { return $this->updateResource($culturalService, $request->validated()); }
    public function destroy(CulturalService $culturalService) { return $this->destroyResource($culturalService); }
}
