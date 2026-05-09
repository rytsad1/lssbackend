<?php

namespace App\Services\Inventory;

use App\Models\Inventory\ItemVariant;
use Illuminate\Database\Eloquent\Collection;

class InventoryStockService
{
    /**
     * Grąžina likučius pagal kiekvieną variantą.
     */
    public function getStockSummary(?int $itemId = null, ?string $search = null): Collection
    {
        $query = ItemVariant::query()
            ->with(['item'])
            ->withSum(['stockBatches as total_quantity' => function ($q) {
                $q->where('quantity_remaining', '>', 0)
                    ->where(function ($q2) {
                        $q2->whereNull('expiration_date')
                            ->orWhereDate('expiration_date', '>=', now());
                    });
            }], 'quantity_remaining')
            ->withSum(['stockBatches as expired_quantity' => function ($q) {
                $q->where('quantity_remaining', '>', 0)
                    ->whereNotNull('expiration_date')
                    ->whereDate('expiration_date', '<', now());
            }], 'quantity_remaining')
            ->withCount(['assetUnits as available_assets_count' => function ($q) {
                $q->where('status', 'in_stock');
            }]);

        if ($itemId) {
            $query->where('item_id', $itemId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhereHas('item', function ($q2) use ($search) {
                        $q2->where('code', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                    });
            });
        }

        return $query->get();
    }

    /**
     * Grąžina detalius vieno varianto likučius pagal partijas.
     */
    public function getVariantStockDetail(int $variantId): array
    {
        $variant = ItemVariant::with([
            'item',
            'stockBatches' => function ($q) {
                $q->where('quantity_remaining', '>', 0)
                    ->orderByRaw('expiration_date IS NULL')
                    ->orderBy('expiration_date')
                    ->orderBy('received_date');
            },
            'assetUnits' => function ($q) {
                $q->where('status', 'in_stock');
            },
        ])->findOrFail($variantId);

        $totalAvailable = $variant->stockBatches
            ->filter(fn ($b) => is_null($b->expiration_date) || $b->expiration_date >= now())
            ->sum('quantity_remaining');

        $totalExpired = $variant->stockBatches
            ->filter(fn ($b) => $b->expiration_date && $b->expiration_date < now())
            ->sum('quantity_remaining');

        return [
            'variant' => $variant,
            'totals' => [
                'available' => (float) $totalAvailable,
                'expired' => (float) $totalExpired,
                'available_assets' => $variant->assetUnits->count(),
            ],
        ];
    }
}
