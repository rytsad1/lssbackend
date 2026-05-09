<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_variant_id' => ['required', 'exists:item_variants,id'],
            'inventory_number' => ['nullable', 'string', 'max:255', 'unique:asset_units,inventory_number'],
            'serial_number' => ['nullable', 'string', 'max:255', 'unique:asset_units,serial_number'],
            'imei' => ['nullable', 'string', 'max:255', 'unique:asset_units,imei'],
            'status' => ['sometimes', 'in:in_stock,reserved,issued,temporary_issued,returned,repair,written_off,lost'],
            'assigned_user_id' => ['nullable', 'exists:user,id_User'],
            'assigned_department_id' => ['nullable', 'exists:department,id_Department'],
            'expiration_date' => ['nullable', 'date'],
            'issued_at' => ['nullable', 'date'],
            'returned_at' => ['nullable', 'date'],
            'written_off_at' => ['nullable', 'date'],
            'write_off_reason' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
