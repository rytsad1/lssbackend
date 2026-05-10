<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KitTemplateItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kit_template_id' => $this->kit_template_id,
            'item_id' => $this->item_id,
            'required_quantity' => $this->required_quantity,
            'size_sensitive' => $this->size_sensitive,
            'must_be_same_batch' => $this->must_be_same_batch,
            'must_be_compatible' => $this->must_be_compatible,
            'prefer_fefo' => $this->prefer_fefo,
            'selection_rules' => $this->selection_rules,
            'item' => new InventoryItemResource($this->whenLoaded('item')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
