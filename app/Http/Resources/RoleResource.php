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
            'permissions' => $this->whenLoaded('permissions', function () {
                return $this->permissions->pluck('id_Premission');
            }),
        ];
    }
}
