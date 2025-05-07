<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id_OrderItem' => $this->id_OrderItem,
            'Quantity' => $this->Quantity,
            'fkOrderid_Order' => $this->fkOrderid_Order,
        ];
    }
}
