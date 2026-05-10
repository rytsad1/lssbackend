<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PreviewKitAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kit_template_id' => ['required', 'exists:kit_templates,id'],
            'user_id' => ['required', 'exists:user,id_User'],

            'measurements_override' => ['nullable', 'array'],
            'measurements_override.clothing_size' => ['nullable', 'string', 'max:20'],
            'measurements_override.shoe_size' => ['nullable', 'string', 'max:10'],
            'measurements_override.head_size' => ['nullable', 'string', 'max:10'],
            'measurements_override.glove_size' => ['nullable', 'string', 'max:10'],
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
