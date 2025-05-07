<?php

namespace App\Http\Requests\OrderItem;

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
            'Quantity' => ['sometimes', 'integer', 'min:1'],
            'fkOrderid_Order' => ['sometimes', 'integer', 'exists:order,id_Order'],
        ];
    }
}
