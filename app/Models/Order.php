<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_Order';
    protected $table = 'order';

    protected $fillable = [
        'Date',
        'State',
        'Type',
        'fkOrderHistoryid_OrderHistory',
        'fkUserid_User',
    ];

    public $timestamps = false;
    public $incrementing = true;
}
