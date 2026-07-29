<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\AnalisisAiRequest;
use App\Http\Resources\AnalisisAiResource;
use App\Models\AnalisisAi;
use Illuminate\Http\Request;

class AnalisisAiController extends ApiResourceController
{
    protected string $model = AnalisisAi::class;
    protected string $resource = AnalisisAiResource::class;

    public function index(Request $request) { return $this->indexResource($request); }
    public function store(AnalisisAiRequest $request) { return $this->storeResource($request->validated()); }
    public function show(AnalisisAi $analisisAi) { return $this->showResource($analisisAi); }
}
