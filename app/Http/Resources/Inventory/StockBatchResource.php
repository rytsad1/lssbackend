<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockBatchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'item_variant_id' => $this->item_variant_id,
            'batch_number' => $this->batch_number,
            'received_date' => $this->received_date,
            'quantity_initial' => $this->quantity_initial,
            'quantity_remaining' => $this->quantity_remaining,
            'expiration_date' => $this->expiration_date,
            'source_reference' => $this->source_reference,
            'notes' => $this->notes,
            'is_expired' => $this->isExpired(),
            'item_variant' => new ItemVariantResource($this->whenLoaded('itemVariant')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
