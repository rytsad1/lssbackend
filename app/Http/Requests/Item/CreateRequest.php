<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Name' => ['required', 'string', 'max:255'],
            'Description' => ['nullable', 'string', 'max:1000'],
            'Price' => ['required', 'numeric', 'min:0'],
            'InventoryNumber' => ['required', 'string', 'max:255'],
            'UnitOfMeasure' => ['required', 'string', 'max:255'],
            'Quantity' => ['required', 'numeric'],
            'fkOrderHistoryid_OrderHistory' => ['nullable', 'integer'],
            'fkOrderItemid_OrderItem' => ['nullable', 'integer'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}

