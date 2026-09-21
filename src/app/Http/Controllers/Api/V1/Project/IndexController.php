<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Api\V1\ApiResourceController;

use App\Http\Requests\Project\IndexRequest;
use App\Http\Resources\Project\IndexResource;
use App\Models\Index;
use Illuminate\Http\Request;

class IndexController extends ApiResourceController
{
    protected string $model = Index::class;

    protected string $resource = IndexResource::class;

    public function index(Request $request)
    {
        $validated = $request->validate([
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'id_proyek' => ['nullable', 'integer', 'exists:proyek,id_proyek'],
        ]);

        $query = Index::query()->with('jenisTutupanLahan');
        if (! empty($validated['id_proyek'])) {
            $query->where('id_proyek', $validated['id_proyek']);
        }

        return IndexResource::collection($query->paginate($validated['per_page'] ?? 15));
    }

    public function store(IndexRequest $request)
    {
        return $this->storeResource($request->validated());
    }

    public function show(Index $index)
    {
        $index->load('jenisTutupanLahan');
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
