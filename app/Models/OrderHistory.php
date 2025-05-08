<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderHistory extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_OrderHistory';
    protected $table = 'orderhistory';

    protected $fillable = [
        'Date',
        'fkOrderid_Order',
        'PerformedByUserid',
        'Action',
        'Comment'
    ];

    public $timestamps = false;
    public $incrementing = true;

    public function order()
    {
        return $this->belongsTo(Order::class, 'fkOrderid_Order', 'id_Order');
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'PerformedByUserid', 'id_User');
    }
}
