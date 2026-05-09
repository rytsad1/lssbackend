<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class IssueInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_variant_id' => ['required', 'exists:item_variants,id'],
            'quantity' => ['required', 'numeric', 'min:0.001'],

            'legacy_user_id' => ['nullable', 'integer'],
            'legacy_department_id' => ['nullable', 'integer'],
            'legacy_order_id' => ['nullable', 'integer'],

            'reason' => ['nullable', 'string'],
        ];
    }
}
