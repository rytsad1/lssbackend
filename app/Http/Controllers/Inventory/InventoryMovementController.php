<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreInventoryMovementRequest;
use App\Http\Resources\Inventory\InventoryMovementResource;
use App\Models\Inventory\InventoryMovement;
use Illuminate\Http\Request;

class InventoryMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryMovement::query()
            ->with(['itemVariant.item', 'stockBatch', 'assetUnit']);

        if ($request->filled('item_variant_id')) {
            $query->where('item_variant_id', $request->integer('item_variant_id'));
        }

        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->string('movement_type')->toString());
        }

        if ($request->filled('legacy_order_id')) {
            $query->where('legacy_order_id', $request->integer('legacy_order_id'));
        }

        $movements = $query->latest('movement_date')->paginate(30);

        return InventoryMovementResource::collection($movements);
    }

    public function store(StoreInventoryMovementRequest $request): InventoryMovementResource
    {
        $movement = InventoryMovement::create($request->validated());

        return new InventoryMovementResource(
            $movement->load(['itemVariant.item', 'stockBatch', 'assetUnit'])
        );
    }

    public function show(InventoryMovement $inventory_movement): InventoryMovementResource
    {
        return new InventoryMovementResource(
            $inventory_movement->load(['itemVariant.item', 'stockBatch', 'assetUnit'])
        );
    }
}
