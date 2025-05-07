<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserRoleResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id_UserRole' => $this->id_UserRole,
            'fkUserid_User' => $this->fkUserid_User,
            'fkRoleid_Role' => $this->fkRoleid_Role,
        ];
    }
}


