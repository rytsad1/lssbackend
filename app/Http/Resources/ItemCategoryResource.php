<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemCategoryResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id_ItemCategory' => $this->id_ItemCategory,
            'Description' => $this->Description,
            'Name' => $this->Name,
            'fkItemid_Item' => $this->fkItemid_Item,
        ];
    }
}
