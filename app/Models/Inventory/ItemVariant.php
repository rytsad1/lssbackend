<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ItemVariant extends Model
{
    use HasFactory;

    protected $table = 'item_variants';

    protected $fillable = [
        'item_id',
        'sku',
        'name',
        'size',
        'color',
        'model',
        'attributes',
        'is_active',
    ];

    protected $casts = [
        'attributes' => 'array',
        'is_active' => 'boolean',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

    public function stockBatches(): HasMany
    {
        return $this->hasMany(StockBatch::class, 'item_variant_id');
    }

    public function assetUnits(): HasMany
    {
        return $this->hasMany(AssetUnit::class, 'item_variant_id');
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class, 'item_variant_id');
    }

    public function stockReservations(): HasMany
    {
        return $this->hasMany(StockReservation::class, 'item_variant_id');
    }

    public function compatibilityGroups(): BelongsToMany
    {
        return $this->belongsToMany(
            CompatibilityGroup::class,
            'item_variant_compatibility',
            'item_variant_id',
            'compatibility_group_id'
        )->withTimestamps();
    }

    public function activeStockBatches(): HasMany
    {
        return $this->hasMany(StockBatch::class, 'item_variant_id')
            ->where('quantity_remaining', '>', 0);
    }

    public function availableAssetUnits(): HasMany
    {
        return $this->hasMany(AssetUnit::class, 'item_variant_id')
            ->where('status', 'in_stock');
    }

    public function getAvailableBatchQuantityAttribute(): float
    {
        return (float) $this->stockBatches()->sum('quantity_remaining');
    }

    public function getAvailableAssetCountAttribute(): int
    {
        return $this->assetUnits()
            ->where('status', 'in_stock')
            ->count();
    }
}
