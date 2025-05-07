<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillOfLadingResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id_BillOfLading' => $this->id_BillOfLading,
            'Date' => $this->Date,
            'Sum' => $this->Sum,
            'Type' => $this->Type,
            'fkOrderid_Order' => $this->fkOrderid_Order,

            // Optional: include related order info
            //'order' => new OrderResource($this->whenLoaded('order')),
        ];
    }
}
