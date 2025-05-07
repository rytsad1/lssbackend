<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use App\Http\Resources\ItemCategoryResource;
use App\Http\Requests\ItemCategory\CreateRequest;
use App\Http\Requests\ItemCategory\UpdateRequest;

class ItemCategoryController extends Controller
{
    public function index()
    {
        return ItemCategoryResource::collection(ItemCategory::all());
    }

    public function store(CreateRequest $request)
    {
        $itemCategory = ItemCategory::create($request->validated());
        return new ItemCategoryResource($itemCategory);
    }

    public function show(ItemCategory $itemCategory)
    {
        return new ItemCategoryResource($itemCategory);
    }

    public function update(UpdateRequest $request, ItemCategory $itemCategory)
    {
        $itemCategory->update($request->validated());
        return new ItemCategoryResource($itemCategory);
    }

    public function destroy(ItemCategory $itemCategory)
    {
        $itemCategory->delete();
        return response()->noContent();
    }
}
