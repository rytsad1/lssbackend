<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryMovementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'item_variant_id' => $this->item_variant_id,
            'stock_batch_id' => $this->stock_batch_id,
            'asset_unit_id' => $this->asset_unit_id,
            'legacy_user_id' => $this->legacy_user_id,
            'legacy_department_id' => $this->legacy_department_id,
            'legacy_order_id' => $this->legacy_order_id,
            'movement_type' => $this->movement_type,
            'quantity' => $this->quantity,
            'movement_date' => $this->movement_date,
            'reason' => $this->reason,
            'context' => $this->context,
            'item_variant' => new ItemVariantResource($this->whenLoaded('itemVariant')),
            'stock_batch' => new StockBatchResource($this->whenLoaded('stockBatch')),
            'asset_unit' => new AssetUnitResource($this->whenLoaded('assetUnit')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
