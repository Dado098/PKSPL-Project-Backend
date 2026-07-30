<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

/** Menyediakan alur CRUD dan response standar untuk resource API. */
abstract class ApiResourceController extends Controller
{
    /** @var class-string<Model> */
    protected string $model;

    /** @var class-string<JsonResource> */
    protected string $resource;

    /** Menampilkan data paginasi dengan batas ukuran halaman yang tervalidasi. */
    protected function indexResource(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate(['per_page' => ['nullable', 'integer', 'min:1', 'max:100']]);

        return $this->resource::collection($this->model::query()->paginate($validated['per_page'] ?? 15));
    }

    /** Membentuk response untuk satu model. */
    protected function showResource(Model $model): JsonResource
    {
        return new $this->resource($model);
    }

    /** Menyimpan atribut tervalidasi dan mengembalikan status created. */
    protected function storeResource(array $attributes): JsonResponse
    {
        $model = $this->model::query()->create($attributes);

        return (new $this->resource($model))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /** Memperbarui model dengan atribut tervalidasi. */
    protected function updateResource(Model $model, array $attributes): JsonResource
    {
        $model->update($attributes);

        return new $this->resource($model->refresh());
    }

    /** Menghapus model dan mengembalikan response tanpa konten. */
    protected function destroyResource(Model $model): Response
    {
        $model->delete();

        return response()->noContent();
    }
}
