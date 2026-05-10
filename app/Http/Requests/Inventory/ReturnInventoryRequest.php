<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReturnInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'inventory_movement_id' => ['required', 'exists:inventory_movements,id'],
            'quantity' => ['required', 'numeric', 'min:0.001'],

            'is_temporary' => ['nullable', 'boolean'],

            'legacy_user_id' => ['nullable', 'integer'],
            'legacy_department_id' => ['nullable', 'integer'],
            'legacy_order_id' => ['nullable', 'integer'],

            'reason' => ['nullable', 'string'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Validacijos klaida',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
