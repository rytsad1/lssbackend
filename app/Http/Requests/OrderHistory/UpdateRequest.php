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
        ];
    }
}
