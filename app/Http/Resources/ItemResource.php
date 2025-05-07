<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id_Item' => $this->id_Item,
            'Name' => $this->Name,
            'Description' => $this->Description,
            'Price' => $this->Price,
            'InventoryNumber' => $this->InventoryNumber,
            'UnitOfMeasure' => $this->UnitOfMeasure,
            'Quantity' => $this->Quantity,
            'fkOrderHistoryid_OrderHistory' => $this->fkOrderHistoryid_OrderHistory,
            'fkOrderItemid_OrderItem' => $this->fkOrderItemid_OrderItem,
        ];
    }
}

