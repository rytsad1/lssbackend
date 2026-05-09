<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_variant_id' => ['required', 'exists:item_variants,id'],
            'stock_batch_id' => ['nullable', 'exists:stock_batches,id'],
            'asset_unit_id' => ['nullable', 'exists:asset_units,id'],
            'legacy_user_id' => ['nullable', 'exists:user,id_User'],
            'legacy_department_id' => ['nullable', 'exists:department,id_Department'],
            'legacy_order_id' => ['nullable', 'exists:order,id_Order'],
            'movement_type' => [
                'required',
                'in:initial_load,manual_adjustment,receipt_sync,issue,temporary_issue,return,temporary_return,writeoff,inventory_gain,inventory_loss,reservation,reservation_release'
            ],
            'quantity' => ['required', 'numeric'],
            'movement_date' => ['required', 'date'],
            'reason' => ['nullable', 'string'],
            'context' => ['nullable', 'array'],
        ];
    }
}
