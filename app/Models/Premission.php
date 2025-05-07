<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Premission extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_Premission';
    protected $table = 'premission';

    protected $fillable = [
        'Name',
        'Description',
        'fkRolePremissionid_RolePremission'
    ];

    public $timestamps = false;
    public $incrementing = true;

    public function rolePermissions()
    {
        return $this->hasMany(RolePremission::class, 'fkPremissionid_Premission');
    }
}
