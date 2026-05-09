<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CompatibilityGroup extends Model
{
    use HasFactory;

    protected $table = 'compatibility_groups';

    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    public function itemVariants(): BelongsToMany
    {
        return $this->belongsToMany(
            ItemVariant::class,
            'item_variant_compatibility',
            'compatibility_group_id',
            'item_variant_id'
        )->withTimestamps();
    }
}
