<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnomalyEvent extends Model
{
    use HasFactory;

    protected $table = 'anomaly_events';

    protected $fillable = [
        'item_variant_id',
        'legacy_department_id',
        'legacy_user_id',
        'anomaly_type',
        'severity',
        'score',
        'summary',
        'details',
        'detected_at',
        'is_resolved',
    ];

    protected $casts = [
        'legacy_department_id' => 'integer',
        'legacy_user_id' => 'integer',
        'score' => 'decimal:4',
        'details' => 'array',
        'detected_at' => 'datetime',
        'is_resolved' => 'boolean',
    ];

    public function itemVariant(): BelongsTo
    {
        return $this->belongsTo(ItemVariant::class, 'item_variant_id');
    }
}
