<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id_Order' => $this->id_Order,
            'Date' => $this->Date,
            'State' => $this->State,
            'Type' => $this->Type,
            'OrderType' => $this->orderType?->name,
            'User' => [
                'id_User' => $this->user?->id_User,
                'Name' => $this->user?->Name,
                'Surname' => $this->user?->Surname,
                'Email' => $this->user?->Email,
            ],
            'OrderItems' => $this->orderItems->map(function ($item) {
                return [
                    'id_Item' => $item->fkItemid_Item,
                    'Name' => $item->item?->Name,
                    'InventoryNumber' => $item->item?->InventoryNumber,
                    'Quantity' => $item->Quantity,
                ];
            }),
            'OrderStatus' => $this->orderStatus?->name,
            'fkOrderHistoryid_OrderHistory' => $this->fkOrderHistoryid_OrderHistory,
        ];
    }
}
