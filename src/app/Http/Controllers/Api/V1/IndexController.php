<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\IndexRequest;
use App\Http\Resources\IndexResource;
use App\Models\Index;
use Illuminate\Http\Request;

class IndexController extends ApiResourceController
{
    protected string $model = Index::class;

    protected string $resource = IndexResource::class;

    public function index(Request $request)
    {
        return $this->indexResource($request);
    }

    public function store(IndexRequest $request)
    {
        return $this->storeResource($request->validated());
    }

    public function show(Index $index)
    {
        return $this->showResource($index);
    }

    public function update(IndexRequest $request, Index $index)
    {
        return $this->updateResource($index, $request->validated());
    }

    public function destroy(Index $index)
    {
        return $this->destroyResource($index);
    }
}
