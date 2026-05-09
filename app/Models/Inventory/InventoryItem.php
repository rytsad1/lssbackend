<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'code',
        'name',
        'description',
        'unit_of_measure',
        'is_expirable',
        'is_asset',
        'is_serialized',
        'is_active',
        'legacy_item_id',
    ];

    protected $casts = [
        'is_expirable' => 'boolean',
        'is_asset' => 'boolean',
        'is_serialized' => 'boolean',
        'is_active' => 'boolean',
        'legacy_item_id' => 'integer',
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(ItemVariant::class, 'item_id');
    }

    public function activeVariants(): HasMany
    {
        return $this->hasMany(ItemVariant::class, 'item_id')
            ->where('is_active', true);
    }
}
