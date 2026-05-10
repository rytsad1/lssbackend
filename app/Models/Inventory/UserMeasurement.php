<?php

namespace App\Models\Inventory;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMeasurement extends Model
{
    use HasFactory;

    protected $table = 'user_measurements';

    protected $fillable = [
        'user_id',
        'clothing_size',
        'shoe_size',
        'head_size',
        'glove_size',
        'height_cm',
        'weight_kg',
        'chest_cm',
        'waist_cm',
        'extra',
        'notes',
    ];

    protected $casts = [
        'extra' => 'array',
        'height_cm' => 'integer',
        'weight_kg' => 'integer',
        'chest_cm' => 'integer',
        'waist_cm' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_User');
    }
}
