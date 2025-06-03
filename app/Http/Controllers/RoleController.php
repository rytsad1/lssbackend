<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Resources\RoleResource;
use App\Http\Requests\Role\CreateRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Models\RolePremission;
use Illuminate\Support\Facades\DB;


class RoleController extends Controller
{
    public function index()
    {
        return RoleResource::collection(Role::all());
    }

    public function store(CreateRequest $request)
    {
        $role = Role::create($request->validated());
        return new RoleResource($role);
    }

    public function show(Role $role)
    {
        $role->load('permissions'); // Reikia įkelti per hasManyThrough
        return new RoleResource($role);
    }


    public function update(UpdateRequest $request, Role $role)
    {
        $role->update($request->validated());
        return new RoleResource($role);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->noContent();
    }
    public function updatePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'integer|exists:premission,id_Premission'
        ]);

        DB::transaction(function () use ($role, $request) {
            // Ištriname senas teises
            RolePremission::where('fk_Role', $role->id_Role)->delete();

            // Sukuriame naujus įrašus
            foreach ($request->permissions as $permissionId) {
                RolePremission::create([
                    'fk_Role' => $role->id_Role,
                    'fk_Permission' => $permissionId
                ]);
            }
        });

        return response()->json(['message' => 'Leidimai atnaujinti sėkmingai.']);
    }
}
