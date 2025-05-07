<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_State';
    protected $table = 'state';

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;
    public $incrementing = true;
}
