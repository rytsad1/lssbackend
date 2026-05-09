<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockAudit extends Model
{
    use HasFactory;

    protected $table = 'stock_audits';

    protected $fillable = [
        'code',
        'legacy_department_id',
        'audit_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'legacy_department_id' => 'integer',
        'audit_date' => 'date',
    ];

    public function lines(): HasMany
    {
        return $this->hasMany(StockAuditLine::class, 'stock_audit_id');
    }
}
