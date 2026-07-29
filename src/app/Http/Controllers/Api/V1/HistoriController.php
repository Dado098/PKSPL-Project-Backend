<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\HistoriRequest;
use App\Http\Resources\HistoriResource;
use App\Models\Histori;
use Illuminate\Http\Request;

class HistoriController extends ApiResourceController
{
    protected string $model = Histori::class;
    protected string $resource = HistoriResource::class;

    public function index(Request $request) { return $this->indexResource($request); }
    public function store(HistoriRequest $request) { return $this->storeResource($request->validated()); }
    public function show(Histori $histori) { return $this->showResource($histori); }
}
