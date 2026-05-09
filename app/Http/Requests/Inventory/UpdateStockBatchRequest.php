<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStockBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_variant_id' => ['sometimes', 'exists:item_variants,id'],
            'batch_number' => ['nullable', 'string', 'max:255'],
            'received_date' => ['nullable', 'date'],
            'quantity_initial' => ['sometimes', 'numeric', 'min:0'],
            'quantity_remaining' => ['sometimes', 'numeric', 'min:0'],
            'expiration_date' => ['nullable', 'date'],
            'source_reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
