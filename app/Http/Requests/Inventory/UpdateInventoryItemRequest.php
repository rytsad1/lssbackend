<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInventoryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $itemId = $this->route('inventory_item')?->id ?? $this->route('inventory_item');

        return [
            'code' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('items', 'code')->ignore($itemId),
            ],
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'unit_of_measure' => ['sometimes', 'string', 'max:50'],
            'is_expirable' => ['sometimes', 'boolean'],
            'is_asset' => ['sometimes', 'boolean'],
            'is_serialized' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'legacy_item_id' => [
                'nullable',
                'integer',
                Rule::unique('items', 'legacy_item_id')->ignore($itemId),
            ],
        ];
    }
}
