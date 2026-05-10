<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUserMeasurementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'clothing_size' => ['nullable', 'string', 'max:20'],
            'shoe_size' => ['nullable', 'string', 'max:10'],
            'head_size' => ['nullable', 'string', 'max:10'],
            'glove_size' => ['nullable', 'string', 'max:10'],

            'height_cm' => ['nullable', 'integer', 'min:50', 'max:250'],
            'weight_kg' => ['nullable', 'integer', 'min:20', 'max:300'],
            'chest_cm' => ['nullable', 'integer', 'min:30', 'max:200'],
            'waist_cm' => ['nullable', 'integer', 'min:30', 'max:200'],

            'extra' => ['nullable', 'array'],
            'notes' => ['nullable', 'string'],
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
