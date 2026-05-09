<?php

namespace App\Models\Inventory;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockBatch extends Model
{
    use HasFactory;

    protected $table = 'stock_batches';

    protected $fillable = [
        'item_variant_id',
        'batch_number',
        'received_date',
        'quantity_initial',
        'quantity_remaining',
        'expiration_date',
        'source_reference',
        'notes',
    ];

    protected $casts = [
        'received_date' => 'date',
        'expiration_date' => 'date',
        'quantity_initial' => 'decimal:3',
        'quantity_remaining' => 'decimal:3',
    ];

    public function itemVariant(): BelongsTo
    {
        return $this->belongsTo(ItemVariant::class, 'item_variant_id');
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class, 'stock_batch_id');
    }

    public function stockReservations(): HasMany
    {
        return $this->hasMany(StockReservation::class, 'stock_batch_id');
    }

    public function stockAuditLines(): HasMany
    {
        return $this->hasMany(StockAuditLine::class, 'stock_batch_id');
    }

    public function isExpired(): bool
    {
        return $this->expiration_date !== null
            && $this->expiration_date->isPast();
    }

    public function expiresWithinDays(int $days): bool
    {
        if (!$this->expiration_date) {
            return false;
        }

        $today = Carbon::today();
        $limit = Carbon::today()->addDays($days);

        return $this->expiration_date->between($today, $limit);
    }
}
