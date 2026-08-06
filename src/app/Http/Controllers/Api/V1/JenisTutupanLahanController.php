<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\JenisTutupanLahanRequest;
use App\Http\Resources\JenisTutupanLahanResource;
use App\Models\JenisTutupanLahan;
use Illuminate\Http\Request;

class JenisTutupanLahanController extends ApiResourceController
{
    protected string $model = JenisTutupanLahan::class;

    protected string $resource = JenisTutupanLahanResource::class;

    public function index(Request $request)
    {
        return $this->indexResource($request);
    }

    public function store(JenisTutupanLahanRequest $request)
    {
        return $this->storeResource($request->validated());
    }

    public function show(JenisTutupanLahan $jenisTutupanLahan)
    {
        return $this->showResource($jenisTutupanLahan);
    }

    public function update(JenisTutupanLahanRequest $request, JenisTutupanLahan $jenisTutupanLahan)
    {
        return $this->updateResource($jenisTutupanLahan, $request->validated());
    }

    public function destroy(JenisTutupanLahan $jenisTutupanLahan)
    {
        return $this->destroyResource($jenisTutupanLahan);
    }
}
