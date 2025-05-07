<?php

namespace App\Http\Requests\OrderItem;

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
            'Quantity' => ['required', 'integer', 'min:1'],
            'fkOrderid_Order' => ['required', 'integer', 'exists:order,id_Order'],
        ];
    }
}
