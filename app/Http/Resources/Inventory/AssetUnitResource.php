<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetUnitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'item_variant_id' => $this->item_variant_id,
            'inventory_number' => $this->inventory_number,
            'serial_number' => $this->serial_number,
            'imei' => $this->imei,
            'status' => $this->status,
            'assigned_user_id' => $this->assigned_user_id,
            'assigned_department_id' => $this->assigned_department_id,
            'expiration_date' => $this->expiration_date,
            'issued_at' => $this->issued_at,
            'returned_at' => $this->returned_at,
            'written_off_at' => $this->written_off_at,
            'write_off_reason' => $this->write_off_reason,
            'notes' => $this->notes,
            'item_variant' => new ItemVariantResource($this->whenLoaded('itemVariant')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
