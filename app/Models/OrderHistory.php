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
    ];

    public $timestamps = false;
    public $incrementing = true;
}
