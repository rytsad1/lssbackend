<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\ReturnInventoryRequest;
use App\Services\Inventory\InventoryReturnService;
use Throwable;

class InventoryReturnController extends Controller
{
    public function __construct(
        protected InventoryReturnService $service
    ) {
    }

    public function store(ReturnInventoryRequest $request)
    {
        try {
            $result = $this->service->return($request->validated());

            return response()->json([
                'message' => 'Grąžinimas atliktas',
                'data' => $result,
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Grąžinimo klaida',
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
