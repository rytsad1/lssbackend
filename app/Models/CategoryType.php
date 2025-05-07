<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryType extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_CategoryType';
    protected $table = 'categorytype';

    protected $fillable = ['name'];

    public $timestamps = false;
    public $incrementing = true;

    public function itemCategories()
    {
        return $this->hasMany(ItemCategory::class, 'Name', 'id_CategoryType');
    }
}
