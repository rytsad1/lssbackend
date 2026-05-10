<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class WriteOffInventoryRequest extends FormRequest
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

            'reason' => ['required', 'string'],
            'writeoff_type' => ['nullable', 'string', 'in:damage,loss,expired,other'],

            'legacy_user_id' => ['nullable', 'integer'],
            'legacy_department_id' => ['nullable', 'integer'],

            'allow_expired' => ['nullable', 'boolean'],
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
