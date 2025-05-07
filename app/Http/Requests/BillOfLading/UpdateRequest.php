<?php

namespace App\Http\Requests\BillOfLading;

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
            'Date' => ['sometimes', 'required', 'date'],
            'Sum' => ['sometimes', 'required', 'numeric', 'min:0'],
            'Type' => ['sometimes', 'required', 'integer', 'exists:OrderType,id_OrderType'],
            'fkOrderid_Order' => ['sometimes', 'required', 'integer', 'exists:order,id_Order'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}
