<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Name' => ['sometimes', 'string', 'max:255'],
            'Description' => ['sometimes', 'string', 'max:1000'],
            'Price' => ['sometimes', 'numeric', 'min:0'],
            'InventoryNumber' => ['sometimes', 'string', 'max:255'],
            'UnitOfMeasure' => ['required', 'string', 'max:255'],
            'Quantity' => ['required', 'numeric'],
            'fkOrderHistoryid_OrderHistory' => ['sometimes', 'integer'],
            'fkOrderItemid_OrderItem' => ['sometimes', 'integer'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}

