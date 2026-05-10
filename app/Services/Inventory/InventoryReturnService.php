<?php

namespace App\Services\Inventory;

use App\Models\Inventory\InventoryMovement;
use App\Models\Inventory\StockBatch;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class InventoryReturnService
{
    public function return(array $data): array
    {
        return DB::transaction(function () use ($data) {

            // randam originalų išdavimo movement
            $sourceMovement = InventoryMovement::query()
                ->where('id', $data['inventory_movement_id'])
                ->whereIn('movement_type', ['issue', 'temporary_issue'])
                ->lockForUpdate()
                ->first();

            if (!$sourceMovement) {
                throw new Exception('Nerastas išdavimo įrašas, kurio pagrindu būtų galima grąžinti.');
            }

            $issuedQuantity = (float) $sourceMovement->quantity;

            // suskaičiuojam, kiek jau grąžinta iš šito išdavimo
            $alreadyReturned = (float) InventoryMovement::query()
                ->whereIn('movement_type', ['return', 'temporary_return'])
                ->where('context->source_movement_id', $sourceMovement->id)
                ->sum('quantity');

            $maxReturnable = $issuedQuantity - $alreadyReturned;

            if ($maxReturnable <= 0) {
                throw new Exception('Šis išdavimas jau pilnai grąžintas.');
            }

            $returnQuantity = (float) $data['quantity'];

            if ($returnQuantity > $maxReturnable) {
                throw new Exception(
                    "Negalima grąžinti daugiau nei buvo išduota. Maksimaliai galima grąžinti: {$maxReturnable}"
                );
            }

            // didinam partijos likutį (jei buvo iš partijos)
            $batch = null;
            if ($sourceMovement->stock_batch_id) {
                $batch = StockBatch::query()
                    ->where('id', $sourceMovement->stock_batch_id)
                    ->lockForUpdate()
                    ->first();

                if ($batch) {
                    $batch->quantity_remaining += $returnQuantity;
                    $batch->save();
                }
            }

            // sukuriam grąžinimo movement
            $movementType = ($data['is_temporary'] ?? false) ? 'temporary_return' : 'return';

            $returnMovement = InventoryMovement::create([
                'item_variant_id' => $sourceMovement->item_variant_id,
                'stock_batch_id' => $sourceMovement->stock_batch_id,
                'asset_unit_id' => $sourceMovement->asset_unit_id,

                'legacy_user_id' => $data['legacy_user_id'] ?? null,
                'legacy_department_id' => $data['legacy_department_id'] ?? null,
                'legacy_order_id' => $data['legacy_order_id'] ?? null,

                'movement_type' => $movementType,

                'quantity' => $returnQuantity,

                'movement_date' => Carbon::now(),

                'reason' => $data['reason'] ?? 'Grąžinimas',

                'context' => [
                    'source_movement_id' => $sourceMovement->id,
                    'returned_to_batch' => $batch?->batch_number,
                ],
            ]);

            return [
                'success' => true,
                'source_movement_id' => $sourceMovement->id,
                'returned_quantity' => $returnQuantity,
                'total_returned' => $alreadyReturned + $returnQuantity,
                'remaining_returnable' => $maxReturnable - $returnQuantity,
                'batch_id' => $batch?->id,
                'batch_remaining_after' => $batch?->quantity_remaining,
                'return_movement_id' => $returnMovement->id,
            ];
        });
    }
}
