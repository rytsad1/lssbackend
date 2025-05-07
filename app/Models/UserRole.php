<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserRole extends Model
{
    use HasFactory;

    protected $table = 'userrole';
    protected $primaryKey = 'id_UserRole';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'fkUserid_User',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'fkUserid_User', 'id_User');
    }
}

