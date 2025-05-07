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
            'fkOrderHistoryid_OrderHistory' => $this->fkOrderHistoryid_OrderHistory,
            'fkUserid_User' => $this->fkUserid_User,
        ];
    }
}
