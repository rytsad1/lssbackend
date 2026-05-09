<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KitTemplateItem extends Model
{
    use HasFactory;

    protected $table = 'kit_template_items';

    protected $fillable = [
        'kit_template_id',
        'item_id',
        'required_quantity',
        'size_sensitive',
        'must_be_same_batch',
        'must_be_compatible',
        'prefer_fefo',
        'selection_rules',
    ];

    protected $casts = [
        'required_quantity' => 'decimal:3',
        'size_sensitive' => 'boolean',
        'must_be_same_batch' => 'boolean',
        'must_be_compatible' => 'boolean',
        'prefer_fefo' => 'boolean',
        'selection_rules' => 'array',
    ];

    public function kitTemplate(): BelongsTo
    {
        return $this->belongsTo(KitTemplate::class, 'kit_template_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }
}
