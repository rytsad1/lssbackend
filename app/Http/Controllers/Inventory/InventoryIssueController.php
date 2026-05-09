<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\IssueInventoryRequest;
use App\Services\Inventory\InventoryIssueService;

class InventoryIssueController extends Controller
{
    public function __construct(
        protected InventoryIssueService $service
    ) {
    }

    public function store(IssueInventoryRequest $request)
    {
        try {

            $result = $this->service->issue(
                $request->validated()
            );

            return response()->json([
                'message' => 'Išdavimas atliktas',
                'data' => $result,
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'message' => 'Išdavimo klaida',
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
