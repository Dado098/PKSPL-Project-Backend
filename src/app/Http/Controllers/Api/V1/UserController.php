<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

/** Menangani endpoint CRUD pengguna. */
class UserController extends ApiResourceController
{
    protected string $model = User::class;
    protected string $resource = UserResource::class;

    // Meneruskan operasi CRUD ke helper dengan request yang sudah tervalidasi.
    public function index(Request $request) { return $this->indexResource($request); }
    public function store(UserRequest $request) { return $this->storeResource($request->validated()); }
    public function show(User $user) { return $this->showResource($user); }
    public function update(UserRequest $request, User $user) { return $this->updateResource($user, $request->validated()); }
    public function destroy(User $user) { return $this->destroyResource($user); }
}
