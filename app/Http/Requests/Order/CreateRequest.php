<?php

namespace App\Http\Requests\Order;

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
            'State' => ['required', 'integer'],
            'Type' => ['required', 'integer'],
            'fkOrderHistoryid_OrderHistory' => ['required', 'integer'],
            'fkUserid_User' => ['required', 'integer'],
        ];
    }
}
