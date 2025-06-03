<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderHistoryResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {

        return [
            'id_OrderHistory' => $this->id_OrderHistory,
            'Date' => $this->Date,
            //'Action' => $this->Action,
            'Comment' => $this->Comment,
            'order' => new OrderResource($this->whenLoaded('order')),
            'performed_by' => new UserResource($this->whenLoaded('performedBy')),
            'order_type' => $this->order?->orderType?->name,
            'items' => $this->order?->orderItems?->map(function ($item) {
                return [
                    'name' => $item->item->Name ?? '–',
                    'inventory_number' => $item->item->InventoryNumber ?? '',
                    'quantity' => $item->Quantity,
                ];
            }),
        ];
    }
}
