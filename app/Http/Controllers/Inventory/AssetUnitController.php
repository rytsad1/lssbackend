<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreAssetUnitRequest;
use App\Http\Requests\Inventory\UpdateAssetUnitRequest;
use App\Http\Resources\Inventory\AssetUnitResource;
use App\Models\Inventory\AssetUnit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssetUnitController extends Controller
{
    public function index(Request $request)
    {
        $query = AssetUnit::query()->with('itemVariant.item');

        if ($request->filled('item_variant_id')) {
            $query->where('item_variant_id', $request->integer('item_variant_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        $units = $query->latest()->paginate(20);

        return AssetUnitResource::collection($units);
    }

    public function store(StoreAssetUnitRequest $request): AssetUnitResource
    {
        $unit = AssetUnit::create($request->validated());

        return new AssetUnitResource($unit->load('itemVariant.item'));
    }

    public function show(AssetUnit $asset_unit): AssetUnitResource
    {
        return new AssetUnitResource($asset_unit->load('itemVariant.item'));
    }

    public function update(UpdateAssetUnitRequest $request, AssetUnit $asset_unit): AssetUnitResource
    {
        $asset_unit->update($request->validated());

        return new AssetUnitResource($asset_unit->fresh()->load('itemVariant.item'));
    }

    public function destroy(AssetUnit $asset_unit): JsonResponse
    {
        if ($asset_unit->inventoryMovements()->exists()) {
            return response()->json([
                'message' => 'Negalima pašalinti vieneto, nes jis jau turi judėjimų istoriją.'
            ], 422);
        }

        $asset_unit->delete();

        return response()->json([
            'message' => 'Vienetas sėkmingai pašalintas.'
        ]);
    }
}
