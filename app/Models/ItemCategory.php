<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_ItemCategory';
    protected $table = 'itemcategory';

    protected $fillable = [
        'Description',
        'Name',
        'fkItemid_Item',
    ];

    public $timestamps = false;
    public $incrementing = true;

    public function categoryType()
    {
        return $this->belongsTo(CategoryType::class, 'Name', 'id_CategoryType');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'fkItemid_Item', 'id_Item');
    }
}
