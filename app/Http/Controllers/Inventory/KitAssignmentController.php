<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\ConfirmKitAssignmentRequest;
use App\Http\Requests\Inventory\PreviewKitAssignmentRequest;
use App\Services\Inventory\KitAssignmentService;
use Throwable;

class KitAssignmentController extends Controller
{
    public function __construct(
        protected KitAssignmentService $service
    ) {
    }

    public function preview(PreviewKitAssignmentRequest $request)
    {
        try {
            $result = $this->service->preview($request->validated());

            return response()->json([
                'data' => $result,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Komplektavimo klaida',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    public function confirm(ConfirmKitAssignmentRequest $request)
    {
        try {
            $result = $this->service->confirm($request->validated());

            return response()->json([
                'message' => 'Komplektas išduotas',
                'data' => $result,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Komplekto išdavimo klaida',
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
