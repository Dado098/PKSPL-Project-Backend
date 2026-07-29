<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\DatasetReferensiRequest;
use App\Http\Resources\DatasetReferensiResource;
use App\Models\DatasetReferensi;
use Illuminate\Http\Request;

class DatasetReferensiController extends ApiResourceController
{
    protected string $model = DatasetReferensi::class;
    protected string $resource = DatasetReferensiResource::class;

    public function index(Request $request) { return $this->indexResource($request); }
    public function store(DatasetReferensiRequest $request) { return $this->storeResource($request->validated()); }
    public function show(DatasetReferensi $datasetReferensi) { return $this->showResource($datasetReferensi); }
    public function update(DatasetReferensiRequest $request, DatasetReferensi $datasetReferensi) { return $this->updateResource($datasetReferensi, $request->validated()); }
    public function destroy(DatasetReferensi $datasetReferensi) { return $this->destroyResource($datasetReferensi); }
}
