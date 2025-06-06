<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderStatus extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_OrderStatus';
    protected $table = 'orderstatus';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
    public $incrementing = true;
}
