<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ValidasiAnalystRequest;
use App\Http\Resources\ValidasiAnalystResource;
use App\Models\ValidasiAnalyst;
use Illuminate\Http\Request;

class ValidasiAnalystController extends ApiResourceController
{
    protected string $model = ValidasiAnalyst::class;
    protected string $resource = ValidasiAnalystResource::class;

    public function index(Request $request) { return $this->indexResource($request); }
    public function store(ValidasiAnalystRequest $request) { return $this->storeResource($request->validated()); }
    public function show(ValidasiAnalyst $validasiAnalyst) { return $this->showResource($validasiAnalyst); }
    public function update(ValidasiAnalystRequest $request, ValidasiAnalyst $validasiAnalyst) { return $this->updateResource($validasiAnalyst, $request->validated()); }
    public function destroy(ValidasiAnalyst $validasiAnalyst) { return $this->destroyResource($validasiAnalyst); }
}
