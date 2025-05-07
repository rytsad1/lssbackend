<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Resources\DepartmentResource;
use App\Http\Requests\Department\CreateRequest;
use App\Http\Requests\Department\UpdateRequest;

class DepartmentController extends Controller
{
    public function index()
    {
        return DepartmentResource::collection(Department::all());
    }

    public function store(CreateRequest $request)
    {
        $department = Department::create($request->validated());
        return new DepartmentResource($department);
    }

    public function show(Department $department)
    {
        return new DepartmentResource($department);
    }

    public function update(UpdateRequest $request, Department $department)
    {
        $department->update($request->validated());
        return new DepartmentResource($department);
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return response()->json(null, 204);
    }
}
