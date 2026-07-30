<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\KabupatenKotaRequest;
use App\Http\Resources\KabupatenKotaResource;
use App\Models\KabupatenKota;
use Illuminate\Http\Request;

/**
 * Endpoint CRUD untuk master data kabupaten atau kota.
 */
class KabupatenKotaController extends ApiResourceController
{
    /** @var class-string<KabupatenKota> */
    protected string $model = KabupatenKota::class;

    /** @var class-string<KabupatenKotaResource> */
    protected string $resource = KabupatenKotaResource::class;

    /**
     * Menampilkan daftar kabupaten atau kota secara paginated.
     */
    public function index(Request $request)
    {
        return $this->indexResource($request);
    }

    /**
     * Membuat kabupaten atau kota baru.
     */
    public function store(KabupatenKotaRequest $request)
    {
        return $this->storeResource($request->validated());
    }

    /**
     * Menampilkan satu kabupaten atau kota berdasarkan primary key schema.
     */
    public function show(KabupatenKota $kabupatenKota)
    {
        return $this->showResource($kabupatenKota);
    }

    /**
     * Memperbarui data kabupaten atau kota yang ada.
     */
    public function update(KabupatenKotaRequest $request, KabupatenKota $kabupatenKota)
    {
        return $this->updateResource($kabupatenKota, $request->validated());
    }

    /**
     * Menghapus kabupaten atau kota yang tidak digunakan lagi.
     */
    public function destroy(KabupatenKota $kabupatenKota)
    {
        return $this->destroyResource($kabupatenKota);
    }
}
