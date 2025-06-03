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
            "Surname" => $this->Surname,
            "Email" => $this->Email,
            "Username" => $this->Username,
            "State" => $this->State,
            "user_roles" => $this->userRoles->map(function ($ur) {
                return [
                    'id_UserRole' => $ur->id_UserRole,
                    'fkUserid_User' => $ur->fkUserid_User,
                    'fkRoleid_Role' => $ur->fkRoleid_Role,
                    'role' => $ur->role ? [
                        'id_Role' => $ur->role->id_Role,
                        'Name' => $ur->role->Name,
                        'role_permissions' => $ur->role->rolePermissions->map(function ($rp) {
                            return [
                                'id_RolePremission' => $rp->id_RolePremission,
                                'fk_Permission' => $rp->fk_Permission,
                                'permission' => $rp->permission
                                    ? [
                                        'id_Premission' => $rp->permission->id_Premission,
                                        'Name' => $rp->permission->Name,
                                        'Description' => $rp->permission->Description
                                    ]
                                    : null
                            ];
                        })
                    ] : null
                ];
            })
        ];
    }

}
