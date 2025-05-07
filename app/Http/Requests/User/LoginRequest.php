<?php

namespace App\Http\Requests\User;

use Illuminate\Support\Arr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Email' => ['required', 'email'],
            'Password' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'Email.required' => 'El. pašto laukelis privalomas!',
            'Email.email' => 'Netinkamas el. pašto formatas!',
            'Password.required' => 'Slaptažodžio laukelis privalomas!',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json($validator->errors(), 422)
        );
    }

    public function all($keys = null): array
    {
        return Arr::only(parent::all(), ['Email', 'Password']);
    }
}
