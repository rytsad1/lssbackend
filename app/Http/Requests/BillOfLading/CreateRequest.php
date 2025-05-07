<?php

namespace App\Http\Requests\BillOfLading;

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
            'Date' => ['required', 'date'],
            'Sum' => ['required', 'numeric', 'min:0'],
            'Type' => ['required', 'integer', 'exists:OrderType,id_OrderType'],
            'fkOrderid_Order' => ['required', 'integer', 'exists:order,id_Order'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}
