<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PremissionResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id_Premission' => $this->id_Premission,
            'Name' => $this->Name,
            'Description' => $this->Description,
            'fkRolePremissionid_RolePremission' => $this->fkRolePremissionid_RolePremission,
        ];
    }
}
