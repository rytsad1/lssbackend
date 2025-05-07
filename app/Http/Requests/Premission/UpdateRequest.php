<?php

namespace App\Http\Requests\Premission;

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
            'Name' => ['sometimes', 'required', 'string', 'max:255'],
            'Description' => ['nullable', 'string', 'max:255'],
            'fkRolePremissionid_RolePremission' => ['sometimes', 'required', 'integer'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
