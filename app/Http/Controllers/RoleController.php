<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Resources\RoleResource;
use App\Http\Requests\Role\CreateRequest;
use App\Http\Requests\Role\UpdateRequest;

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
}
