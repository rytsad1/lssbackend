<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryIssueLog extends Model
{
    protected $table = 'temporaryissuelog';
    protected $primaryKey = 'id_TemporaryIssueLog';
    public $timestamps = false;

    protected $fillable = [
        'fkItemid_Item',
        'fkUserid_User',
        'IssuedDate',
        'ReturnedDate',
        'Comment',
        'Quantity'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'fkItemid_Item', 'id_Item');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'fkUserid_User', 'id_User');
    }
}
