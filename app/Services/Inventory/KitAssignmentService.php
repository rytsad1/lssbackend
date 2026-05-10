<?php

namespace App\Services\Inventory;

use App\Models\Inventory\AssetUnit;
use App\Models\Inventory\InventoryMovement;
use App\Models\Inventory\ItemVariant;
use App\Models\Inventory\KitTemplate;
use App\Models\Inventory\KitTemplateItem;
use App\Models\Inventory\StockBatch;
use App\Models\Inventory\UserMeasurement;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class KitAssignmentService
{
    /**
     * Sugeneruoja pasiūlymą — dar nieko neišduoda.
     */
    public function preview(array $data): array
    {
        $template = KitTemplate::with('items.item.variants.stockBatches', 'items.item.variants.assetUnits')
            ->findOrFail($data['kit_template_id']);

        $measurements = $this->getEffectiveMeasurements(
            $data['user_id'],
            $data['measurements_override'] ?? []
        );

        $proposed = [];
        $allAvailable = true;

        foreach ($template->items as $kitItem) {
            $proposal = $this->proposeForItem($kitItem, $measurements);

            if (!$proposal['available']) {
                $allAvailable = false;
            }

            $proposed[] = $proposal;
        }

        return [
            'kit_template_id' => $template->id,
            'kit_code' => $template->code,
            'kit_name' => $template->name,
            'user_id' => $data['user_id'],
            'measurements_used' => $measurements,
            'all_available' => $allAvailable,
            'items' => $proposed,
        ];
    }

    /**
     * Patvirtina ir atlieka realų išdavimą per InventoryIssueService logiką.
     */
    public function confirm(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $issuedMovements = [];
            $movementType = ($data['is_temporary'] ?? false) ? 'temporary_issue' : 'issue';
            $reason = $data['reason'] ?? 'Komplekto išdavimas';

            foreach ($data['items'] as $row) {
                if (!empty($row['asset_unit_id'])) {
                    // turto vienetas — keičiam statusą
                    $movement = $this->issueAsset(
                        (int) $row['asset_unit_id'],
                        (int) $data['user_id'],
                        $data['legacy_department_id'] ?? null,
                        $movementType,
                        $reason
                    );
                    $issuedMovements[] = $movement;
                } else {
                    // kiekinis — FEFO/FIFO
                    $movements = $this->issueQuantity(
                        (int) $row['item_variant_id'],
                        (float) $row['quantity'],
                        (int) $data['user_id'],
                        $data['legacy_department_id'] ?? null,
                        $movementType,
                        $reason
                    );
                    $issuedMovements = array_merge($issuedMovements, $movements);
                }
            }

            return [
                'success' => true,
                'movement_type' => $movementType,
                'movements' => $issuedMovements,
            ];
        });
    }

    private function getEffectiveMeasurements(int $userId, array $override): array
    {
        $stored = UserMeasurement::where('user_id', $userId)->first();

        $base = [
            'clothing_size' => $stored?->clothing_size,
            'shoe_size' => $stored?->shoe_size,
            'head_size' => $stored?->head_size,
            'glove_size' => $stored?->glove_size,
        ];

        // override užbrauks tik tuos laukus, kurie pateikti
        foreach ($override as $key => $value) {
            if ($value !== null && $value !== '') {
                $base[$key] = $value;
            }
        }

        return $base;
    }

    private function proposeForItem(KitTemplateItem $kitItem, array $measurements): array
    {
        $item = $kitItem->item;
        $required = (float) $kitItem->required_quantity;

        $base = [
            'kit_item_id' => $kitItem->id,
            'item_id' => $item->id,
            'item_code' => $item->code,
            'item_name' => $item->name,
            'required_quantity' => $required,
            'size_sensitive' => $kitItem->size_sensitive,
            'prefer_fefo' => $kitItem->prefer_fefo,
        ];

        // 1) ASSET (vienetinis turtas)
        if ($item->is_asset || $item->is_serialized) {
            return $this->proposeAsset($base, $item, $required, $measurements, $kitItem);
        }

        // 2) Kiekinis daiktas su dydžiu
        if ($kitItem->size_sensitive) {
            return $this->proposeSizedQuantity($base, $item, $required, $measurements, $kitItem);
        }

        // 3) Paprastas kiekinis daiktas
        return $this->proposeSimpleQuantity($base, $item, $required, $kitItem);
    }

    private function proposeAsset(array $base, $item, float $required, array $measurements, KitTemplateItem $kitItem): array
    {
        // ieškom laisvo asset_unit per visus item variantus
        $variantQuery = $item->variants();

        // jei size_sensitive ir turim dydį
        $size = $kitItem->size_sensitive ? ($measurements['clothing_size'] ?? null) : null;

        if ($size) {
            $variantQuery->where('size', $size);
        }

        $variantIds = $variantQuery->pluck('id');

        $assets = AssetUnit::whereIn('item_variant_id', $variantIds)
            ->where('status', 'in_stock')
            ->limit((int) $required)
            ->with('itemVariant')
            ->get();

        if ($assets->count() < $required) {
            return array_merge($base, [
                'type' => 'asset',
                'available' => false,
                'reason' => 'Nepakanka laisvų vienetų',
                'found_count' => $assets->count(),
                'needed_count' => (int) $required,
                'selected' => [],
            ]);
        }

        return array_merge($base, [
            'type' => 'asset',
            'available' => true,
            'selected' => $assets->map(fn ($a) => [
                'asset_unit_id' => $a->id,
                'inventory_number' => $a->inventory_number,
                'serial_number' => $a->serial_number,
                'imei' => $a->imei,
                'item_variant_id' => $a->item_variant_id,
                'variant_name' => $a->itemVariant?->name,
                'quantity' => 1,
            ])->all(),
        ]);
    }

    private function proposeSizedQuantity(array $base, $item, float $required, array $measurements, KitTemplateItem $kitItem): array
    {
        // pasirenkam, kuri matavimo reikšmė tinka pagal selection_rules arba default = clothing_size
        $sizeKey = $kitItem->selection_rules['size_field'] ?? 'clothing_size';
        $size = $measurements[$sizeKey] ?? null;

        if (!$size) {
            return array_merge($base, [
                'type' => 'quantity',
                'available' => false,
                'reason' => "Nežinomas nario dydis (laukas: {$sizeKey})",
                'selected' => [],
            ]);
        }

        $variant = $item->variants()->where('size', $size)->first();

        if (!$variant) {
            return array_merge($base, [
                'type' => 'quantity',
                'available' => false,
                'reason' => "Nėra varianto su dydžiu {$size}",
                'requested_size' => $size,
                'selected' => [],
            ]);
        }

        return $this->pickFromBatches($base, $variant, $required, $kitItem, $size);
    }

    private function proposeSimpleQuantity(array $base, $item, float $required, KitTemplateItem $kitItem): array
    {
        // imam pirmą variantą (DEFAULT arba pirmą prieinamą)
        $variant = $item->variants()
            ->where('is_active', true)
            ->orderBy('id')
            ->first();

        if (!$variant) {
            return array_merge($base, [
                'type' => 'quantity',
                'available' => false,
                'reason' => 'Daiktas neturi variantų',
                'selected' => [],
            ]);
        }

        return $this->pickFromBatches($base, $variant, $required, $kitItem);
    }

    private function pickFromBatches(array $base, ItemVariant $variant, float $required, KitTemplateItem $kitItem, ?string $size = null): array
    {
        $query = StockBatch::query()
            ->where('item_variant_id', $variant->id)
            ->where('quantity_remaining', '>', 0)
            ->where(function ($q) {
                $q->whereNull('expiration_date')
                    ->orWhereDate('expiration_date', '>=', now());
            });

        // FEFO + FIFO eilė
        $query->orderByRaw('expiration_date IS NULL')
            ->orderBy('expiration_date')
            ->orderBy('received_date');

        $batches = $query->get();

        $remaining = $required;
        $picked = [];

        foreach ($batches as $batch) {
            if ($remaining <= 0) break;

            $take = min((float) $batch->quantity_remaining, $remaining);

            $picked[] = [
                'stock_batch_id' => $batch->id,
                'batch_number' => $batch->batch_number,
                'expiration_date' => $batch->expiration_date,
                'item_variant_id' => $variant->id,
                'variant_name' => $variant->name,
                'variant_size' => $variant->size,
                'quantity' => $take,
            ];

            $remaining -= $take;
        }

        if ($remaining > 0) {
            return array_merge($base, [
                'type' => 'quantity',
                'available' => false,
                'reason' => 'Nepakanka likučio',
                'requested_size' => $size,
                'item_variant_id' => $variant->id,
                'variant_name' => $variant->name,
                'needed' => $required,
                'found' => $required - $remaining,
                'selected' => $picked,
            ]);
        }

        return array_merge($base, [
            'type' => 'quantity',
            'available' => true,
            'requested_size' => $size,
            'item_variant_id' => $variant->id,
            'variant_name' => $variant->name,
            'selected' => $picked,
        ]);
    }

    private function issueAsset(int $assetUnitId, int $userId, ?int $departmentId, string $movementType, string $reason): array
    {
        $asset = AssetUnit::lockForUpdate()->findOrFail($assetUnitId);

        if ($asset->status !== 'in_stock') {
            throw new Exception("Vienetas #{$asset->id} nėra laisvas (status: {$asset->status})");
        }

        $asset->status = $movementType === 'temporary_issue' ? 'temporary_issued' : 'issued';
        $asset->assigned_user_id = $userId;
        $asset->assigned_department_id = $departmentId;
        $asset->issued_at = Carbon::now();
        $asset->save();

        $movement = InventoryMovement::create([
            'item_variant_id' => $asset->item_variant_id,
            'stock_batch_id' => null,
            'asset_unit_id' => $asset->id,

            'legacy_user_id' => $userId,
            'legacy_department_id' => $departmentId,
            'legacy_order_id' => null,

            'movement_type' => $movementType,
            'quantity' => 1,
            'movement_date' => Carbon::now(),
            'reason' => $reason,

            'context' => [
                'source' => 'kit_assignment',
                'asset_inventory_number' => $asset->inventory_number,
            ],
        ]);

        return [
            'movement_id' => $movement->id,
            'asset_unit_id' => $asset->id,
            'inventory_number' => $asset->inventory_number,
            'quantity' => 1,
        ];
    }

    private function issueQuantity(int $variantId, float $quantity, int $userId, ?int $departmentId, string $movementType, string $reason): array
    {
        $batches = StockBatch::query()
            ->where('item_variant_id', $variantId)
            ->where('quantity_remaining', '>', 0)
            ->where(function ($q) {
                $q->whereNull('expiration_date')
                    ->orWhereDate('expiration_date', '>=', now());
            })
            ->orderByRaw('expiration_date IS NULL')
            ->orderBy('expiration_date')
            ->orderBy('received_date')
            ->lockForUpdate()
            ->get();

        $remaining = $quantity;
        $movements = [];

        foreach ($batches as $batch) {
            if ($remaining <= 0) break;

            $take = min((float) $batch->quantity_remaining, $remaining);

            $batch->quantity_remaining -= $take;
            $batch->save();

            $movement = InventoryMovement::create([
                'item_variant_id' => $variantId,
                'stock_batch_id' => $batch->id,
                'asset_unit_id' => null,

                'legacy_user_id' => $userId,
                'legacy_department_id' => $departmentId,
                'legacy_order_id' => null,

                'movement_type' => $movementType,
                'quantity' => $take,
                'movement_date' => Carbon::now(),
                'reason' => $reason,

                'context' => [
                    'source' => 'kit_assignment',
                    'issued_from_batch' => $batch->batch_number,
                ],
            ]);

            $movements[] = [
                'movement_id' => $movement->id,
                'item_variant_id' => $variantId,
                'stock_batch_id' => $batch->id,
                'quantity' => $take,
            ];

            $remaining -= $take;
        }

        if ($remaining > 0) {
            throw new Exception("Nepakanka likučio variantui #{$variantId}. Trūksta: {$remaining}");
        }

        return $movements;
    }
}
