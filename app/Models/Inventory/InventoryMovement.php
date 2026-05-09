<?php

namespace App\Models\Inventory;

use App\Models\Department;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $table = 'inventory_movements';

    protected $fillable = [
        'item_variant_id',
        'stock_batch_id',
        'asset_unit_id',
        'legacy_user_id',
        'legacy_department_id',
        'legacy_order_id',
        'movement_type',
        'quantity',
        'movement_date',
        'reason',
        'context',
    ];

    protected $casts = [
        'stock_batch_id' => 'integer',
        'asset_unit_id' => 'integer',
        'legacy_user_id' => 'integer',
        'legacy_department_id' => 'integer',
        'legacy_order_id' => 'integer',
        'quantity' => 'decimal:3',
        'movement_date' => 'datetime',
        'context' => 'array',
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

    public function legacyUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'legacy_user_id', 'id_User');
    }

    public function legacyDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'legacy_department_id', 'id_Department');
    }

    public function legacyOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'legacy_order_id', 'id_Order');
    }

    public function isIssue(): bool
    {
        return in_array($this->movement_type, ['issue', 'temporary_issue'], true);
    }

    public function isReturn(): bool
    {
        return in_array($this->movement_type, ['return', 'temporary_return'], true);
    }

    public function isWriteOff(): bool
    {
        return $this->movement_type === 'writeoff';
    }
}
