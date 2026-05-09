<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAuditLine extends Model
{
    use HasFactory;

    protected $table = 'stock_audit_lines';

    protected $fillable = [
        'stock_audit_id',
        'item_variant_id',
        'stock_batch_id',
        'system_quantity',
        'physical_quantity',
        'difference_quantity',
        'comment',
    ];

    protected $casts = [
        'system_quantity' => 'decimal:3',
        'physical_quantity' => 'decimal:3',
        'difference_quantity' => 'decimal:3',
    ];

    public function stockAudit(): BelongsTo
    {
        return $this->belongsTo(StockAudit::class, 'stock_audit_id');
    }

    public function itemVariant(): BelongsTo
    {
        return $this->belongsTo(ItemVariant::class, 'item_variant_id');
    }

    public function stockBatch(): BelongsTo
    {
        return $this->belongsTo(StockBatch::class, 'stock_batch_id');
    }
}
