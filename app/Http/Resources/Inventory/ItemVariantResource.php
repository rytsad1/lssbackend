<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemVariantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'item_id' => $this->item_id,
            'sku' => $this->sku,
            'name' => $this->name,
            'size' => $this->size,
            'color' => $this->color,
            'model' => $this->model,
            'attributes' => $this->attributes,
            'is_active' => $this->is_active,
            'available_batch_quantity' => $this->available_batch_quantity ?? null,
            'available_asset_count' => $this->available_asset_count ?? null,
            'item' => new InventoryItemResource($this->whenLoaded('item')),
            'stock_batches' => StockBatchResource::collection($this->whenLoaded('stockBatches')),
            'asset_units' => AssetUnitResource::collection($this->whenLoaded('assetUnits')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
