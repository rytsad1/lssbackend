<?php

namespace App\Http\Controllers;

use App\Models\CategoryType;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryTypeResource;

class CategoryTypeController extends Controller
{
    public function index()
    {
        return CategoryTypeResource::collection(CategoryType::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $categoryType = CategoryType::create($data);
        return new CategoryTypeResource($categoryType);
    }

    public function show(CategoryType $categoryType)
    {
        return new CategoryTypeResource($categoryType);
    }

    public function update(Request $request, CategoryType $categoryType)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $categoryType->update($data);
        return new CategoryTypeResource($categoryType);
    }

    public function destroy(CategoryType $categoryType)
    {
        $categoryType->delete();
        return response(null, 204);
    }
}
