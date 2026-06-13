<?php

namespace App\Http\Requests\Auth;

use App\Enums\Messages\AuthValidationMessages;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'first_name' => [
             'required',
            'string',
             'min:2',
             'max:50',
            ],

            'last_name' => [
              'required',
              'string',
              'min:2',
              'max:50',
            ],
            'username' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'alpha_dash',
                'unique:users,username',
            ],

            'email' => [
            'required',
            'email',
            'max:255',
            'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',

                Password::min(8)->mixedCase()->numbers(),
            ],

              'birth_date' => [
               'required',
               'date',
               'before:' . now()->subYears(14)->format('Y-m-d'),
              ],

        ];
    }
    public function messages(): array
    {
        return [
            'first_name.required' => __(AuthValidationMessages::RegisterFirstNameRequired->value),
            'first_name.min' => __(AuthValidationMessages::RegisterFirstNameMin->value),
            'first_name.max' => __(AuthValidationMessages::RegisterFirstNameMax->value),

            'last_name.required' => __(AuthValidationMessages::RegisterLastNameRequired->value),
            'last_name.min' => __(AuthValidationMessages::RegisterLastNameMin->value),
            'last_name.max' => __(AuthValidationMessages::RegisterLastNameMax->value),

            'username.required' => __(AuthValidationMessages::RegisterUsernameRequired->value),
            'username.unique' => __(AuthValidationMessages::RegisterUsernameUnique->value),
            'username.alpha_dash' => __(AuthValidationMessages::RegisterUsernameAlphaDash->value),
            'username.min' => __(AuthValidationMessages::RegisterUsernameMin->value),
            'username.max' => __(AuthValidationMessages::RegisterUsernameMax->value),

            'email.required' => __(AuthValidationMessages::RegisterEmailRequired->value),
            'email.email' => __(AuthValidationMessages::RegisterEmailInvalid->value),
            'email.unique' => __(AuthValidationMessages::RegisterEmailUnique->value),

            'birth_date.required' => __(AuthValidationMessages::RegisterBirthDateRequired->value),
            'birth_date.date' => __(AuthValidationMessages::RegisterBirthDateInvalid->value),
            'birth_date.before' => __(AuthValidationMessages::RegisterBirthDateBefore->value),

            'password.required' => __(AuthValidationMessages::RegisterPasswordRequired->value),
            'password.confirmed' => __(AuthValidationMessages::RegisterPasswordConfirmed->value),
        ];
    }
}
