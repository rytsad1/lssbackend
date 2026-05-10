<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\WriteOffAssetRequest;
use App\Http\Requests\Inventory\WriteOffInventoryRequest;
use App\Services\Inventory\InventoryWriteOffService;
use Throwable;

class InventoryWriteOffController extends Controller
{
    public function __construct(
        protected InventoryWriteOffService $service
    ) {
    }

    public function quantity(WriteOffInventoryRequest $request)
    {
        try {
            $result = $this->service->writeOffQuantity($request->validated());

            return response()->json([
                'message' => 'Nurašymas atliktas',
                'data' => $result,
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Nurašymo klaida',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    public function asset(WriteOffAssetRequest $request)
    {
        try {
            $result = $this->service->writeOffAsset($request->validated());

            return response()->json([
                'message' => 'Vienetas nurašytas',
                'data' => $result,
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Vieneto nurašymo klaida',
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
