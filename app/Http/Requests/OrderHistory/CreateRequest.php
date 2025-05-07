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
        ];
    }
}
