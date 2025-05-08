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
            'fkItemid_Item' => ['sometimes', 'integer', 'exists:item,id_Item'],
            'ReturnedQuantity' => ['sometimes', 'integer', 'min:0'],
            'WriteOffReason' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
