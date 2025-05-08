<?php

namespace App\Http\Requests\OrderHistory;

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
            'Date' => ['required', 'date'],
            'fkOrderid_Order' => ['required', 'exists:order,id_Order'],
            'PerformedByUserid' => ['required', 'exists:user,id_User'],
            'Action' => ['required', 'string', 'max:100'],
            'Comment' => ['nullable', 'string', 'max:255'],
        ];
    }
}
