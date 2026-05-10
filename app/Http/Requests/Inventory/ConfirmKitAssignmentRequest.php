<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ConfirmKitAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kit_template_id' => ['required', 'exists:kit_templates,id'],
            'user_id' => ['required', 'exists:user,id_User'],
            'legacy_department_id' => ['nullable', 'integer'],

            'is_temporary' => ['nullable', 'boolean'],
            'reason' => ['nullable', 'string'],

            // patvirtintos eilutės iš preview
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_variant_id' => ['required', 'exists:item_variants,id'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.001'],
            'items.*.asset_unit_id' => ['nullable', 'exists:asset_units,id'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Validacijos klaida',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
