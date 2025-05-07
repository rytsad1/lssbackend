<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id_Role' => $this->id_Role,
            'Name' => $this->Name,
            'Description' => $this->Description,
            'fkUserRoleid_UserRole' => $this->fkUserRoleid_UserRole,
            'fkRolePremissionid_RolePremission' => $this->fkRolePremissionid_RolePremission,
        ];
    }
}
