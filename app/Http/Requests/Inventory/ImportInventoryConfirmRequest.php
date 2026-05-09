<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class ImportInventoryConfirmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array'],
            'items.*.code' => ['required', 'string', 'max:255'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.unit_of_measure' => ['required', 'string', 'max:50'],
            'items.*.quantity' => ['required', 'numeric', 'min:0'],
            'items.*.price' => ['nullable', 'numeric', 'min:0'],
            'items.*.description' => ['nullable', 'string'],
            'items.*.is_asset' => ['nullable', 'boolean'],
            'items.*.is_serialized' => ['nullable', 'boolean'],
            'items.*.is_expirable' => ['nullable', 'boolean'],
            'items.*.expiration_date' => ['nullable', 'date'],
            'items.*.batch_number' => ['nullable', 'string', 'max:255'],
        ];
    }
}
