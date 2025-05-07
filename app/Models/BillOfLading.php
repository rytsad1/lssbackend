<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillOfLading extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_BillOfLading';
    protected $table = 'billoflading';

    protected $fillable = [
        "Date",
        "Sum",
        "Type",
        "fkOrderid_Order",
    ];

    public $timestamps = false;
    public $incrementing = true;

    // Santykis su OrderType
    public function orderType()
    {
        return $this->belongsTo(OrderType::class, 'Type', 'id_OrderType');
    }

    // Santykis su Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'fkOrderid_Order', 'id_Order');
    }
}
