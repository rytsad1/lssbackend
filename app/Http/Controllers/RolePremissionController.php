<?php

namespace App\Http\Controllers;

use App\Models\RolePremission;
use Illuminate\Http\Request;
use App\Http\Resources\RolePremissionResource;
use App\Http\Requests\RolePremission\CreateRequest;
use App\Http\Requests\RolePremission\UpdateRequest;

class RolePremissionController extends Controller
{
    public function index()
    {
        return RolePremissionResource::collection(RolePremission::all());
    }

    public function store(CreateRequest $request)
    {
        $data = $request->validated();
        $rolePremission = RolePremission::create($data);
        return new RolePremissionResource($rolePremission);
    }

    public function show(RolePremission $rolePremission)
    {
        return new RolePremissionResource($rolePremission);
    }

    public function update(UpdateRequest $request, RolePremission $rolePremission)
    {
        $rolePremission->update($request->validated());
        return new RolePremissionResource($rolePremission);
    }

    public function destroy(RolePremission $rolePremission)
    {
        $rolePremission->delete();
        return response()->noContent();
    }
}

