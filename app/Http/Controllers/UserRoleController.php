<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Http\Resources\UserRoleResource;
use App\Http\Requests\UserRole\CreateRequest;
use App\Http\Requests\UserRole\UpdateRequest;

class UserRoleController extends Controller
{
    public function index()
    {
        return UserRoleResource::collection(UserRole::all());
    }

    public function store(CreateRequest $request)
    {
        $userRole = UserRole::create($request->validated());
        return new UserRoleResource($userRole);
    }

    public function show(UserRole $userRole)
    {
        return new UserRoleResource($userRole);
    }

    public function update(UpdateRequest $request, UserRole $userRole)
    {
        $userRole->update($request->validated());
        return new UserRoleResource($userRole);
    }

    public function destroy(UserRole $userRole)
    {
        $userRole->delete();
        return response()->noContent();
    }
}

