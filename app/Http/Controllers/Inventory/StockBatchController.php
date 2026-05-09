<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreStockBatchRequest;
use App\Http\Requests\Inventory\UpdateStockBatchRequest;
use App\Http\Resources\Inventory\StockBatchResource;
use App\Models\Inventory\StockBatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockBatchController extends Controller
{
    public function index(Request $request)
    {
        $query = StockBatch::query()->with('itemVariant.item');

        if ($request->filled('item_variant_id')) {
            $query->where('item_variant_id', $request->integer('item_variant_id'));
        }

        if ($request->filled('expiring_within_days')) {
            $days = (int) $request->expiring_within_days;
            $query->whereNotNull('expiration_date')
                ->whereDate('expiration_date', '<=', now()->addDays($days));
        }

        $batches = $query->orderBy('expiration_date')->paginate(20);

        return StockBatchResource::collection($batches);
    }

    public function store(StoreStockBatchRequest $request): StockBatchResource
    {
        $batch = StockBatch::create($request->validated());

        return new StockBatchResource($batch->load('itemVariant.item'));
    }

    public function show(StockBatch $stock_batch): StockBatchResource
    {
        return new StockBatchResource($stock_batch->load('itemVariant.item'));
    }

    public function update(UpdateStockBatchRequest $request, StockBatch $stock_batch): StockBatchResource
    {
        $stock_batch->update($request->validated());

        return new StockBatchResource($stock_batch->fresh()->load('itemVariant.item'));
    }

    public function destroy(StockBatch $stock_batch): JsonResponse
    {
        if ($stock_batch->inventoryMovements()->exists()) {
            return response()->json([
                'message' => 'Negalima pašalinti partijos, nes ji jau turi judėjimų istoriją.'
            ], 422);
        }

        $stock_batch->delete();

        return response()->json([
            'message' => 'Partija sėkmingai pašalinta.'
        ]);
    }
}
