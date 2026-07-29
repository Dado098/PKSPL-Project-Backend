<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ProyekRequest;
use App\Http\Resources\ProyekResource;
use App\Models\Proyek;
use Illuminate\Http\Request;

class ProyekController extends ApiResourceController
{
    protected string $model = Proyek::class;
    protected string $resource = ProyekResource::class;

    public function index(Request $request) { return $this->indexResource($request); }
    public function store(ProyekRequest $request) { return $this->storeResource($request->validated()); }
    public function show(Proyek $proyek) { return $this->showResource($proyek); }
    public function update(ProyekRequest $request, Proyek $proyek) { return $this->updateResource($proyek, $request->validated()); }
    public function destroy(Proyek $proyek) { return $this->destroyResource($proyek); }
}
