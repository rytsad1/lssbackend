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

    protected $fillable = [
        'fk_Role',
        'fk_Permission'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'fkRoleid_Role');
    }

    public function premission()
    {
        return $this->belongsTo(Premission::class, 'fkPremissionid_Premission', 'id_Premission');
    }
}
