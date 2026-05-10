<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateKitTemplateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_id' => ['sometimes', 'exists:items,id'],
            'required_quantity' => ['sometimes', 'numeric', 'min:0.001'],

            'size_sensitive' => ['sometimes', 'boolean'],
            'must_be_same_batch' => ['sometimes', 'boolean'],
            'must_be_compatible' => ['sometimes', 'boolean'],
            'prefer_fefo' => ['sometimes', 'boolean'],

            'selection_rules' => ['nullable', 'array'],
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
