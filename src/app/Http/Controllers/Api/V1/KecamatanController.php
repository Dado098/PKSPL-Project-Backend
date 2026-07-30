<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\KecamatanRequest;
use App\Http\Resources\KecamatanResource;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

/**
 * Endpoint CRUD untuk master data kecamatan.
 */
class KecamatanController extends ApiResourceController
{
    /** @var class-string<Kecamatan> */
    protected string $model = Kecamatan::class;

    /** @var class-string<KecamatanResource> */
    protected string $resource = KecamatanResource::class;

    /**
     * Menampilkan daftar kecamatan secara paginated.
     */
    public function index(Request $request)
    {
        return $this->indexResource($request);
    }

    /**
     * Membuat kecamatan baru.
     */
    public function store(KecamatanRequest $request)
    {
        return $this->storeResource($request->validated());
    }

    /**
     * Menampilkan satu kecamatan berdasarkan primary key schema.
     */
    public function show(Kecamatan $kecamatan)
    {
        return $this->showResource($kecamatan);
    }

    /**
     * Memperbarui data kecamatan yang ada.
     */
    public function update(KecamatanRequest $request, Kecamatan $kecamatan)
    {
        return $this->updateResource($kecamatan, $request->validated());
    }

    /**
     * Menghapus kecamatan yang tidak digunakan lagi.
     */
    public function destroy(Kecamatan $kecamatan)
    {
        return $this->destroyResource($kecamatan);
    }
}
