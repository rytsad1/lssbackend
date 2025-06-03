<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Name' => ['sometimes', 'string', 'min:2', 'max:50'],
            'Surname' => ['sometimes', 'string', 'min:2', 'max:50'],
            'Email' => ['sometimes', 'email', 'unique:user,Email,' . $this->route('user')->id_User . ',id_User'],
            'Username' => ['sometimes', 'string', 'min:4', 'max:50', 'unique:user,Username,' . $this->route('user')->id_User . ',id_User'],
            'Password' => [
                'sometimes',
                'string',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised()
            ],
            'State' => ['sometimes', 'integer'],
            'fkOrderHistoryid_OrderHistory' => ['sometimes', 'integer'],
            'fkBillOfLadingid_BillOfLading' => ['sometimes', 'integer'],
            'role_id' => ['nullable|exists:role,id_Role'],
            'permissions' => ['nullable|array'],
            'permissions.*' => ['integer|exists:premission,id_Premission'],
            'RoleIds' => ['required', 'array'],
            'RoleIds.*' => ['exists:role,id_Role'],
        ];
    }

    public function messages(): array
    {
        return [
            'Email.unique' => 'Toks el. paštas jau naudojamas.',
            'Username.unique' => 'Toks vartotojo vardas jau egzistuoja.',
            'Password.confirmed' => 'Slaptažodžiai nesutampa!',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }

    protected function prepareForValidation(): void
    {
        // jei naudotum JWT ir nori riboti, kad user galėtų redaguoti tik save – gali įterpti čia papildomą logiką
        // šiuo metu nedarome automatinio laukų pašalinimo
    }

//    protected function passedValidation(): void
//    {
//        if ($this->filled('Password')) {
//            $this->merge([
//                'Password' => Hash::make($this->Password)
//            ]);
//        }
//    }
}
