<?php

namespace App\Models\Inventory;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetUnit extends Model
{
    use HasFactory;

    protected $table = 'asset_units';

    protected $fillable = [
        'item_variant_id',
        'inventory_number',
        'serial_number',
        'imei',
        'status',
        'assigned_user_id',
        'assigned_department_id',
        'expiration_date',
        'issued_at',
        'returned_at',
        'written_off_at',
        'write_off_reason',
        'notes',
    ];

    protected $casts = [
        'assigned_user_id' => 'integer',
        'assigned_department_id' => 'integer',
        'expiration_date' => 'date',
        'issued_at' => 'datetime',
        'returned_at' => 'datetime',
        'written_off_at' => 'datetime',
    ];

    public function itemVariant(): BelongsTo
    {
        return $this->belongsTo(ItemVariant::class, 'item_variant_id');
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class, 'asset_unit_id');
    }

    public function stockReservations(): HasMany
    {
        return $this->hasMany(StockReservation::class, 'asset_unit_id');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id', 'id_User');
    }

    public function assignedDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'assigned_department_id', 'id_Department');
    }

    public function isAvailable(): bool
    {
        return $this->status === 'in_stock';
    }

    public function isIssued(): bool
    {
        return in_array($this->status, ['issued', 'temporary_issued'], true);
    }

    public function isWrittenOff(): bool
    {
        return $this->status === 'written_off';
    }
}
