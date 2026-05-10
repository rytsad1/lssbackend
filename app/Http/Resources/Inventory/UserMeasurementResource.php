<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserMeasurementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'clothing_size' => $this->clothing_size,
            'shoe_size' => $this->shoe_size,
            'head_size' => $this->head_size,
            'glove_size' => $this->glove_size,
            'height_cm' => $this->height_cm,
            'weight_kg' => $this->weight_kg,
            'chest_cm' => $this->chest_cm,
            'waist_cm' => $this->waist_cm,
            'extra' => $this->extra,
            'notes' => $this->notes,
            'updated_at' => $this->updated_at,
        ];
    }
}
