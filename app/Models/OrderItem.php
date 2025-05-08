<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_OrderItem';
    protected $table = 'orderitem';

    protected $fillable = [
        'Quantity',
        'fkOrderid_Order',
        'fkItemid_Item',
        'ReturnedQuantity',
        'WriteOffReason'
    ];

    public $timestamps = false;
    public $incrementing = true;

    public function order()
    {
        return $this->belongsTo(Order::class, 'fkOrderid_Order', 'id_Order');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'fkItemid_Item', 'id_Item');
    }
}
