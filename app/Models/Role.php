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
    ];

    public $timestamps = false;

    public function userRoles()
    {
        return $this->hasMany(UserRole::class, 'fkRoleid_Role');
    }

    public function rolePermissions()
    {
        return $this->hasMany(RolePremission::class, 'fk_Role', 'id_Role');
    }

    public function permissions()
    {
        return $this->hasManyThrough(
            Premission::class,
            RolePremission::class,
            'fk_Role',       // Foreign key RolePremission -> Role
            'id_Premission', // Local key Premission
            'id_Role',       // Local key Role
            'fk_Permission'  // Foreign key RolePremission -> Premission
        );
    }

}
