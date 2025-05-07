<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class DepartmentResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id_Department' => $this->id_Department,
            'Name' => $this->Name,
            'Description' => $this->Description,
            'Address' => $this->Address,
            'fkUserid_User' => $this->fkUserid_User,
            'fkBillOfLadingid_BillOfLading' => $this->fkBillOfLadingid_BillOfLading,
        ];
    }
}
