<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RolePremission extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_RolePremission';
    protected $table = 'rolepremission';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [];
}
