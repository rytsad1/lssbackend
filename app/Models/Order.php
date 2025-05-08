<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'order';
    protected $primaryKey = 'id_Order';
    public $timestamps = false;

    protected $fillable = [
        'Date',
        'State',
        'Type',
        'fkOrderHistoryid_OrderHistory',
        'fkUserid_User',
        'fkOrderTypeid_OrderType',
        'fkOrderStatusid_OrderStatus',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'fkUserid_User', 'id_User');
    }

    public function orderHistory(): BelongsTo
    {
        return $this->belongsTo(OrderHistory::class, 'fkOrderHistoryid_OrderHistory', 'id_OrderHistory');
    }

    public function orderType(): BelongsTo
    {
        return $this->belongsTo(OrderType::class, 'fkOrderTypeid_OrderType', 'id_OrderType');
    }

    public function orderStatus(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class, 'fkOrderStatusid_OrderStatus', 'id_OrderStatus');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'fkOrderid_Order', 'id_Order');
    }
}
