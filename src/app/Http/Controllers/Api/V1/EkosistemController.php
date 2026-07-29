<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\EkosistemRequest;
use App\Http\Resources\EkosistemResource;
use App\Models\Ekosistem;
use Illuminate\Http\Request;

class EkosistemController extends ApiResourceController
{
    protected string $model = Ekosistem::class;
    protected string $resource = EkosistemResource::class;

    public function index(Request $request) { return $this->indexResource($request); }
    public function store(EkosistemRequest $request) { return $this->storeResource($request->validated()); }
    public function show(Ekosistem $ekosistem) { return $this->showResource($ekosistem); }
    public function update(EkosistemRequest $request, Ekosistem $ekosistem) { return $this->updateResource($ekosistem, $request->validated()); }
    public function destroy(Ekosistem $ekosistem) { return $this->destroyResource($ekosistem); }
}
