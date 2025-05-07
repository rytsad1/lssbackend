<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    public function state()
    {
        return $this->belongsTo(State::class, 'State', 'id_State');
    }


    public function hasPermission(string $key): bool
    {
        return $this->userRoles
                ->flatMap(fn($role) => $role->role->rolePermissions)
                ->pluck('permission.key')
                ->contains($key) || $this->hasPermission('everything');
    }


}
