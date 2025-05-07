<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Name' => ['required', 'alpha', 'min:2', 'max:50'],
            'Surname' => ['required', 'alpha', 'min:2', 'max:50'],
            'Email' => ['required', 'email', 'unique:user,Email'],
            'Username' => ['required', 'string', 'min:4', 'max:50', 'unique:user,Username'],
            'Password' => [
                'required',
                'string',
                'confirmed', // Reikalaus ir `Password_confirmation` lauko
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'Name.required' => 'Vardo laukelis privalomas.',
            'Name.alpha' => 'Varde gali būti tik raidės.',
            'Surname.required' => 'Pavardės laukelis privalomas.',
            'Email.required' => 'El. pašto laukelis privalomas.',
            'Email.unique' => 'Toks el. paštas jau naudojamas.',
            'Username.required' => 'Vartotojo vardas privalomas.',
            'Username.unique' => 'Toks vartotojo vardas jau egzistuoja.',
            'Password.required' => 'Slaptažodis privalomas.',
            'Password.confirmed' => 'Slaptažodžiai nesutampa.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

    protected function passedValidation(): void
    {
        $password = $this->Password;
        $data = $this->only(array_keys($this->rules()));
        $data['Password'] = Hash::make($password);
        $this->replace($data);
    }
}
