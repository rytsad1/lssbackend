<?php

namespace App\Http\Requests\OrderHistory;

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
            'Date' => ['sometimes', 'date'],
            'fkOrderid_Order' => ['sometimes', 'exists:order,id_Order'],
            'PerformedByUserid' => ['sometimes', 'exists:user,id_User'],
            'Action' => ['sometimes', 'string', 'max:100'],
            'Comment' => ['nullable', 'string', 'max:255'],
        ];
    }
}
