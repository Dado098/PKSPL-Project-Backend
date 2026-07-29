<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\MetodeValuasiRequest;
use App\Http\Resources\MetodeValuasiResource;
use App\Models\MetodeValuasi;
use Illuminate\Http\Request;

class MetodeValuasiController extends ApiResourceController
{
    protected string $model = MetodeValuasi::class;
    protected string $resource = MetodeValuasiResource::class;

    public function index(Request $request) { return $this->indexResource($request); }
    public function store(MetodeValuasiRequest $request) { return $this->storeResource($request->validated()); }
    public function show(MetodeValuasi $metodeValuasi) { return $this->showResource($metodeValuasi); }
    public function update(MetodeValuasiRequest $request, MetodeValuasi $metodeValuasi) { return $this->updateResource($metodeValuasi, $request->validated()); }
    public function destroy(MetodeValuasi $metodeValuasi) { return $this->destroyResource($metodeValuasi); }
}
