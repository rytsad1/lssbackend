<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_Department';
    protected $table = 'department';

    protected $fillable = [
        'Name',
        'Description',
        'Address',
        'fkUserid_User',
        'fkBillOfLadingid_BillOfLading',
    ];

    public $timestamps = false;
    public $incrementing = true;
}
