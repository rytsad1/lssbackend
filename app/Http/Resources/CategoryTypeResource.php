<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryTypeResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request)
    {
        return [
            'id_CategoryType' => $this->id_CategoryType,
            'name' => $this->name,
        ];
    }
}
