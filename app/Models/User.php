<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    protected $primaryKey = 'id_User';
    protected $table = 'user';
    protected $fillable = [
        'Name',
        'Surname',
        'Email',
        'Username',
        'Password',
        'State',
        'fkOrderHistoryid_OrderHistory',
        'fkBillOfLadingid_BillOfLading'
    ];
    public $timestamps = false;
    public $incrementing = true;
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            "Email" => $this->Email,
            //"Role" => $this->Role,
            "aud" => env('JWT_AUDIENCE', 'default')
        ];
    }
    /** Relationships */
    public function userRoles()
    {
        return $this->hasMany(UserRole::class, 'fkUserid_User', 'id_User');
    }

    public function billOfLading()
    {
        return $this->belongsTo(BillOfLading::class, 'fkBillOfLadingid_BillOfLading', 'id_BillOfLading');
    }

    public function orderHistory()
    {
        return $this->belongsTo(OrderHistory::class, 'fkOrderHistoryid_OrderHistory', 'id_OrderHistory');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'userrole', 'fkUserid_User', 'fkRoleid_Role');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'State', 'id_State');
    }


    public function hasPermission(string $key): bool
    {
        $permissions = $this->userRoles
            ->flatMap(fn($role) => $role->role->rolePermissions)
            ->pluck('premission.Name')
            ->map(fn($p) => strtolower($p))
            ->unique();

        return $permissions->contains('everything') || $permissions->contains(strtolower($key));
    }


    public function setPasswordAttribute($value)
    {
        if ($value && Hash::needsRehash($value)) {
            $this->attributes['Password'] = bcrypt($value);
        } else {
            $this->attributes['Password'] = $value;
        }
    }
    public function isWarehouseManager(): bool
    {
        return $this->roles()->whereIn('Name', ['Sandėlininkas'])->exists();

    }



}
