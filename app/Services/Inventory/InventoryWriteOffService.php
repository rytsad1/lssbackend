<?php

namespace App\Services\Inventory;

use App\Models\Inventory\AssetUnit;
use App\Models\Inventory\InventoryMovement;
use App\Models\Inventory\StockBatch;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class InventoryWriteOffService
{
    /**
     * Kiekinių prekių nurašymas — mažina partijų likučius.
     * Eilė: FEFO (greičiausiai gendantys pirmi), tada FIFO.
     */
    public function writeOffQuantity(array $data): array
    {
        return DB::transaction(function () use ($data) {

            $allowExpired = $data['allow_expired'] ?? false;
            $writeoffType = $data['writeoff_type'] ?? 'other';

            // jei nurašom dėl pasibaigusio galiojimo, leidžiam expired partijas
            if ($writeoffType === 'expired') {
                $allowExpired = true;
            }

            $query = StockBatch::query()
                ->where('item_variant_id', $data['item_variant_id'])
                ->where('quantity_remaining', '>', 0);

            if (!$allowExpired) {
                $query->where(function ($q) {
                    $q->whereNull('expiration_date')
                        ->orWhereDate('expiration_date', '>=', now());
                });
            } else {
                // expired pirma — kad nurašytume būtent juos
                $query->orderByRaw('expiration_date IS NULL');
                $query->orderBy('expiration_date');
            }

            $batches = $query
                ->orderByRaw('expiration_date IS NULL')
                ->orderBy('expiration_date')
                ->orderBy('received_date')
                ->lockForUpdate()
                ->get();

            $remainingToWriteOff = (float) $data['quantity'];
            $writtenOff = [];

            foreach ($batches as $batch) {
                if ($remainingToWriteOff <= 0) {
                    break;
                }

                $available = (float) $batch->quantity_remaining;

                if ($available <= 0) {
                    continue;
                }

                $take = min($available, $remainingToWriteOff);

                $batch->quantity_remaining -= $take;
                $batch->save();

                $movement = InventoryMovement::create([
                    'item_variant_id' => $batch->item_variant_id,
                    'stock_batch_id' => $batch->id,
                    'asset_unit_id' => null,

                    'legacy_user_id' => $data['legacy_user_id'] ?? null,
                    'legacy_department_id' => $data['legacy_department_id'] ?? null,
                    'legacy_order_id' => null,

                    'movement_type' => 'writeoff',

                    'quantity' => $take,

                    'movement_date' => Carbon::now(),

                    'reason' => $data['reason'],

                    'context' => [
                        'writeoff_type' => $writeoffType,
                        'writeoff_from_batch' => $batch->batch_number,
                    ],
                ]);

                $writtenOff[] = [
                    'batch_id' => $batch->id,
                    'batch_number' => $batch->batch_number,
                    'written_off_quantity' => $take,
                    'remaining_after' => (float) $batch->quantity_remaining,
                    'movement_id' => $movement->id,
                ];

                $remainingToWriteOff -= $take;
            }

            if ($remainingToWriteOff > 0) {
                throw new Exception(
                    "Nepakanka likučio nurašymui. Trūksta: {$remainingToWriteOff}"
                );
            }

            return [
                'success' => true,
                'requested_quantity' => (float) $data['quantity'],
                'written_off_quantity' => (float) $data['quantity'],
                'writeoff_type' => $writeoffType,
                'written_off_batches' => $writtenOff,
            ];
        });
    }

    /**
     * Vienetinio turto nurašymas — keičia statusą.
     */
    public function writeOffAsset(array $data): array
    {
        return DB::transaction(function () use ($data) {

            $asset = AssetUnit::query()
                ->where('id', $data['asset_unit_id'])
                ->lockForUpdate()
                ->first();

            if (!$asset) {
                throw new Exception('Vienetas nerastas.');
            }

            if ($asset->status === 'written_off') {
                throw new Exception('Vienetas jau nurašytas.');
            }

            $writeoffType = $data['writeoff_type'] ?? 'other';

            $asset->status = 'written_off';
            $asset->written_off_at = Carbon::now();
            $asset->write_off_reason = $data['reason'];
            $asset->save();

            $movement = InventoryMovement::create([
                'item_variant_id' => $asset->item_variant_id,
                'stock_batch_id' => null,
                'asset_unit_id' => $asset->id,

                'legacy_user_id' => $data['legacy_user_id'] ?? null,
                'legacy_department_id' => $data['legacy_department_id'] ?? null,
                'legacy_order_id' => null,

                'movement_type' => 'writeoff',

                'quantity' => 1,

                'movement_date' => Carbon::now(),

                'reason' => $data['reason'],

                'context' => [
                    'writeoff_type' => $writeoffType,
                    'asset_inventory_number' => $asset->inventory_number,
                    'asset_serial_number' => $asset->serial_number,
                ],
            ]);

            return [
                'success' => true,
                'asset_unit_id' => $asset->id,
                'inventory_number' => $asset->inventory_number,
                'serial_number' => $asset->serial_number,
                'new_status' => $asset->status,
                'written_off_at' => $asset->written_off_at,
                'movement_id' => $movement->id,
            ];
        });
    }
}
