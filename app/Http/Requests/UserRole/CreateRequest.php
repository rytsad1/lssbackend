<?php

namespace App\Http\Requests\UserRole;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fkUserid_User' => ['required', 'integer', 'exists:user,id_User'],
        ];
    }
}

