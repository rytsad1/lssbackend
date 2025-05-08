<?php

namespace App\Http\Requests\Order;

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
            'State' => ['sometimes', 'integer'],
            'Type' => ['sometimes', 'integer'],
            'fkOrderHistoryid_OrderHistory' => ['sometimes', 'integer'],
            'fkUserid_User' => ['sometimes', 'integer'],
            'fkOrderTypeid_OrderType' => ['sometimes', 'integer'],
            'fkOrderStatusid_OrderStatus' => ['sometimes', 'integer'],
        ];
    }
}
