<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends ApiResourceController
{
    protected string $model = Role::class;
    protected string $resource = RoleResource::class;

    public function index(Request $request) { return $this->indexResource($request); }
    public function store(RoleRequest $request) { return $this->storeResource($request->validated()); }
    public function show(Role $role) { return $this->showResource($role); }
    public function update(RoleRequest $request, Role $role) { return $this->updateResource($role, $request->validated()); }
    public function destroy(Role $role) { return $this->destroyResource($role); }
}
