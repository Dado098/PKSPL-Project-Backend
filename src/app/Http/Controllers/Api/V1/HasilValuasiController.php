<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\HasilValuasiRequest;
use App\Http\Resources\HasilValuasiResource;
use App\Models\HasilValuasi;
use Illuminate\Http\Request;

/** Menangani endpoint CRUD hasil valuasi. */
class HasilValuasiController extends ApiResourceController
{
    protected string $model = HasilValuasi::class;
    protected string $resource = HasilValuasiResource::class;

    // Meneruskan operasi CRUD ke helper dengan request yang sudah tervalidasi.
    public function index(Request $request) { return $this->indexResource($request); }
    public function store(HasilValuasiRequest $request) { return $this->storeResource($request->validated()); }
    public function show(HasilValuasi $hasilValuasi) { return $this->showResource($hasilValuasi); }
    public function update(HasilValuasiRequest $request, HasilValuasi $hasilValuasi) { return $this->updateResource($hasilValuasi, $request->validated()); }
    public function destroy(HasilValuasi $hasilValuasi) { return $this->destroyResource($hasilValuasi); }
}
