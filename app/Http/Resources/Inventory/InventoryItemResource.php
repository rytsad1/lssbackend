<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'unit_of_measure' => $this->unit_of_measure,
            'is_expirable' => $this->is_expirable,
            'is_asset' => $this->is_asset,
            'is_serialized' => $this->is_serialized,
            'is_active' => $this->is_active,
            'legacy_item_id' => $this->legacy_item_id,
            'variants_count' => $this->whenCounted('variants'),
            'variants' => ItemVariantResource::collection($this->whenLoaded('variants')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
