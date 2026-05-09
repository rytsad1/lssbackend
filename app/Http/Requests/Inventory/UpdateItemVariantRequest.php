<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateItemVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $variantId = $this->route('item_variant')?->id ?? $this->route('item_variant');

        return [
            'item_id' => ['sometimes', 'exists:items,id'],
            'sku' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('item_variants', 'sku')->ignore($variantId),
            ],
            'name' => ['sometimes', 'string', 'max:255'],
            'size' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:50'],
            'model' => ['nullable', 'string', 'max:100'],
            'attributes' => ['nullable', 'array'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
