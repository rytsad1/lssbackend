<?php

namespace App\Http\Requests\Department;

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
            'Description' => ['sometimes', 'string'],
            'Address' => ['sometimes', 'string'],
            'fkUserid_User' => ['sometimes', 'integer'],
            'fkBillOfLadingid_BillOfLading' => ['sometimes', 'integer'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
