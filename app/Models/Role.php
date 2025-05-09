<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_Role';
    protected $table = 'role';

    protected $fillable = [
        'Name',
        'Description',
        'fkUserRoleid_UserRole',
        'fkRolePremissionid_RolePremission',
    ];

    public $timestamps = false;
    public $incrementing = true;

    public function userRoles()
    {
        return $this->hasMany(UserRole::class, 'fkRoleid_Role');
    }

    public function rolePermissions()
    {
        return $this->hasMany(RolePremission::class, 'fkRoleid_Role', 'id_Role');
    }

}

