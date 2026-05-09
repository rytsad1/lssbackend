<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreItemVariantRequest;
use App\Http\Requests\Inventory\UpdateItemVariantRequest;
use App\Http\Resources\Inventory\ItemVariantResource;
use App\Models\Inventory\ItemVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemVariantController extends Controller
{
    public function index(Request $request)
    {
        $query = ItemVariant::query()->with(['item']);

        if ($request->filled('item_id')) {
            $query->where('item_id', $request->integer('item_id'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('size', 'like', "%{$search}%")
                    ->orWhere('color', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
            });
        }

        $variants = $query->latest()->paginate(20);

        return ItemVariantResource::collection($variants);
    }

    public function store(StoreItemVariantRequest $request): ItemVariantResource
    {
        $variant = ItemVariant::create($request->validated());

        return new ItemVariantResource($variant->load('item'));
    }

    public function show(ItemVariant $item_variant): ItemVariantResource
    {
        return new ItemVariantResource(
            $item_variant->load(['item', 'stockBatches', 'assetUnits'])
        );
    }

    public function update(UpdateItemVariantRequest $request, ItemVariant $item_variant): ItemVariantResource
    {
        $item_variant->update($request->validated());

        return new ItemVariantResource(
            $item_variant->fresh()->load(['item', 'stockBatches', 'assetUnits'])
        );
    }

    public function destroy(ItemVariant $item_variant): JsonResponse
    {
        if ($item_variant->stockBatches()->exists() || $item_variant->assetUnits()->exists()) {
            return response()->json([
                'message' => 'Negalima pašalinti varianto, nes jis jau turi partijų arba vienetų.'
            ], 422);
        }

        $item_variant->delete();

        return response()->json([
            'message' => 'Variantas sėkmingai pašalintas.'
        ]);
    }
}
