<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:255', 'unique:items,code'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'unit_of_measure' => ['required', 'string', 'max:50'],
            'is_expirable' => ['sometimes', 'boolean'],
            'is_asset' => ['sometimes', 'boolean'],
            'is_serialized' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'legacy_item_id' => ['nullable', 'integer', 'unique:items,legacy_item_id'],
        ];
    }
}
