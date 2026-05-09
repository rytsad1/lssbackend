<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreInventoryItemRequest;
use App\Http\Requests\Inventory\UpdateInventoryItemRequest;
use App\Http\Resources\Inventory\InventoryItemResource;
use App\Models\Inventory\InventoryItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryItem::query()->withCount('variants');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        $items = $query->latest()->paginate(20);

        return InventoryItemResource::collection($items);
    }

    public function store(StoreInventoryItemRequest $request): InventoryItemResource
    {
        $item = InventoryItem::create($request->validated());

        return new InventoryItemResource($item->loadCount('variants'));
    }

    public function show(InventoryItem $inventory_item): InventoryItemResource
    {
        return new InventoryItemResource(
            $inventory_item->load(['variants'])->loadCount('variants')
        );
    }

    public function update(UpdateInventoryItemRequest $request, InventoryItem $inventory_item): InventoryItemResource
    {
        $inventory_item->update($request->validated());

        return new InventoryItemResource(
            $inventory_item->fresh()->load(['variants'])->loadCount('variants')
        );
    }

    public function destroy(InventoryItem $inventory_item): JsonResponse
    {
        if ($inventory_item->variants()->exists()) {
            return response()->json([
                'message' => 'Negalima pašalinti daikto, nes jis turi variantų.'
            ], 422);
        }

        $inventory_item->delete();

        return response()->json([
            'message' => 'Daiktas sėkmingai pašalintas.'
        ]);
    }
}
