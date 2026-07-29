<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;

/**
 * Endpoint baca untuk master data role.
 *
 * Perubahan role tidak dibuka lewat API sebelum matriks akses role tersedia.
 */
class RoleController extends ApiResourceController
{
    /** @var class-string<Role> */
    protected string $model = Role::class;

    /** @var class-string<RoleResource> */
    protected string $resource = RoleResource::class;

    /**
     * Menampilkan role secara paginated.
     */
    public function index(Request $request)
    {
        return $this->indexResource($request);
    }

    /**
     * Menampilkan satu role berdasarkan primary key schema.
     */
    public function show(Role $role)
    {
        return $this->showResource($role);
    }
}
