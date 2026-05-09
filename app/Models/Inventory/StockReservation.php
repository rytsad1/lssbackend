<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockReservation extends Model
{
    use HasFactory;

    protected $table = 'stock_reservations';

    protected $fillable = [
        'item_variant_id',
        'stock_batch_id',
        'asset_unit_id',
        'legacy_user_id',
        'legacy_department_id',
        'legacy_order_id',
        'quantity_reserved',
        'status',
        'reserved_until',
        'reason',
    ];

    protected $casts = [
        'quantity_reserved' => 'decimal:3',
        'reserved_until' => 'datetime',
        'legacy_user_id' => 'integer',
        'legacy_department_id' => 'integer',
        'legacy_order_id' => 'integer',
    ];

    public function itemVariant(): BelongsTo
    {
        return $this->belongsTo(ItemVariant::class, 'item_variant_id');
    }

    public function stockBatch(): BelongsTo
    {
        return $this->belongsTo(StockBatch::class, 'stock_batch_id');
    }

    public function assetUnit(): BelongsTo
    {
        return $this->belongsTo(AssetUnit::class, 'asset_unit_id');
    }
}
