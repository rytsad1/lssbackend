<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_Item';
    protected $table = 'item';

    protected $fillable = [
        'Name',
        'Description',
        'Price',
        'InventoryNumber',
        'UnitOfMeasure',
        'Quantity',
        'fkOrderHistoryid_OrderHistory',
        'fkOrderItemid_OrderItem',
    ];

    public $timestamps = false;
    public $incrementing = true;
}

