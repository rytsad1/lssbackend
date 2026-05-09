<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Services\Inventory\InventoryStockService;
use Illuminate\Http\Request;

class InventoryStockController extends Controller
{
    public function __construct(
        protected InventoryStockService $service
    ) {
    }

    public function index(Request $request)
    {
        $stock = $this->service->getStockSummary(
            $request->integer('item_id') ?: null,
            $request->string('search')->toString() ?: null
        );

        return response()->json([
            'data' => $stock->map(function ($variant) {
                return [
                    'variant_id' => $variant->id,
                    'item_id' => $variant->item_id,
                    'item_code' => $variant->item->code ?? null,
                    'item_name' => $variant->item->name ?? null,
                    'sku' => $variant->sku,
                    'variant_name' => $variant->name,
                    'size' => $variant->size,
                    'color' => $variant->color,
                    'total_quantity' => (float) ($variant->total_quantity ?? 0),
                    'expired_quantity' => (float) ($variant->expired_quantity ?? 0),
                    'available_assets_count' => $variant->available_assets_count ?? 0,
                ];
            }),
        ]);
    }

    public function show(int $variant)
    {
        $detail = $this->service->getVariantStockDetail($variant);

        return response()->json([
            'data' => $detail,
        ]);
    }
}
