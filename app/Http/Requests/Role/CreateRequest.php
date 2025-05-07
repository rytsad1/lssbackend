<?php

namespace App\Http\Requests\Role;

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
            'Name' => ['required', 'string', 'max:255'],
            'Description' => ['nullable', 'string', 'max:1000'],
            'fkUserRoleid_UserRole' => ['required', 'integer', 'exists:userrole,id_UserRole'],
            'fkRolePremissionid_RolePremission' => ['required', 'integer', 'exists:rolepremission,id_RolePremission'],
        ];
    }
}
