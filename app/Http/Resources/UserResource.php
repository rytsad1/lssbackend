<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            "id_User" => $this->id_User,
            "Name" => $this->Name,
            "Email" => $this->Email,
            "Username" => $this->Username,
            "State" => $this->State,
            "fkOrderHistoryid_OrderHistory" => $this->fkOrderHistoryid_OrderHistory,
            "fkBillOfLadingid_BillOfLading" => $this->fkBillOfLadingid_BillOfLading,

        ];
    }
}
