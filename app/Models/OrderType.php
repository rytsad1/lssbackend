<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderType extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_OrderType';
    protected $table = 'ordertype';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
    public $incrementing = true;
}
