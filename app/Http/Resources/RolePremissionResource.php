<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RolePremissionResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id_RolePremission' => $this->id_RolePremission,
            'fk_Role'=>$this->fk_Role,
            'fk_Permission'=>$this->fk_Permission,
        ];
    }
}

