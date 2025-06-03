<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WriteOffLog extends Model
{
    protected $table = 'writeofflog';
    protected $primaryKey = 'id_WriteOffLog';
    public $timestamps = false;

    protected $fillable = [
        'fkItemid_Item',
        'Quantity',
        'Reason',
        'Date',
        'HandledByUserid'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'fkItemid_Item');
    }

    public function handledBy()
    {
        return $this->belongsTo(User::class, 'HandledByUserid');
    }
}
