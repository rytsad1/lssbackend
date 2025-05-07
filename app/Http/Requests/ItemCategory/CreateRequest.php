<?php

namespace App\Http\Requests\ItemCategory;

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
            'Description' => 'nullable|string|max:255',
            'Name' => 'required|exists:categorytype,id_CategoryType',
            'fkItemid_Item' => 'required|exists:item,id_Item',
        ];
    }
}
