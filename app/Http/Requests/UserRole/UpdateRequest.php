<?php

namespace App\Http\Requests\UserRole;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fkUserid_User' => ['sometimes', 'required', 'integer', 'exists:user,id_User'],
        ];
    }
}
