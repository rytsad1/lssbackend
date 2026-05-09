<?php

namespace App\Services\Inventory;

use App\Models\Inventory\StockBatch;
use App\Models\Inventory\InventoryMovement;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryIssueService
{
    public function issue(array $data): array
    {
        return DB::transaction(function () use ($data) {

            $requiredQuantity = $data['quantity'];

            $batches = StockBatch::query()
                ->where('item_variant_id', $data['item_variant_id'])
                ->where('quantity_remaining', '>', 0)

                // FEFO
                ->orderByRaw('expiration_date IS NULL')
                ->orderBy('expiration_date')

                // FIFO
                ->orderBy('received_date')

                ->lockForUpdate()
                ->get();

            $remainingToIssue = $requiredQuantity;

            $issued = [];

            foreach ($batches as $batch) {

                if ($remainingToIssue <= 0) {
                    break;
                }

                $available = $batch->quantity_remaining;

                if ($available <= 0) {
                    continue;
                }

                $take = min($available, $remainingToIssue);

                // mažinam likutį
                $batch->quantity_remaining -= $take;
                $batch->save();

                // kuriam movement
                $movement = InventoryMovement::create([
                    'item_variant_id' => $batch->item_variant_id,
                    'stock_batch_id' => $batch->id,
                    'asset_unit_id' => null,

                    'legacy_user_id' => $data['legacy_user_id'] ?? null,
                    'legacy_department_id' => $data['legacy_department_id'] ?? null,
                    'legacy_order_id' => $data['legacy_order_id'] ?? null,

                    'movement_type' => 'issue',

                    'quantity' => $take,

                    'movement_date' => Carbon::now(),

                    'reason' => $data['reason'] ?? 'Išdavimas',

                    'context' => [
                        'issued_from_batch' => $batch->batch_number,
                    ],
                ]);

                $issued[] = [
                    'batch_id' => $batch->id,
                    'batch_number' => $batch->batch_number,
                    'issued_quantity' => $take,
                    'remaining_after' => $batch->quantity_remaining,
                    'movement_id' => $movement->id,
                ];

                $remainingToIssue -= $take;
            }

            // neužteko likučio
            if ($remainingToIssue > 0) {
                throw new \Exception(
                    "Nepakanka likučio. Trūksta {$remainingToIssue}"
                );
            }

            return [
                'success' => true,
                'requested_quantity' => $requiredQuantity,
                'issued_quantity' => $requiredQuantity,
                'issued_batches' => $issued,
            ];
        });
    }
}
