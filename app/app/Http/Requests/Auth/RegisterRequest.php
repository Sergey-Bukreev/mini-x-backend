<?php

namespace App\Http\Requests\Auth;

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
            'first_name.required' => 'First name is required.',
            'first_name.min' => 'First name must be at least 2 characters.',
            'first_name.max' => 'First name may not be greater than 50 characters.',

            'last_name.required' => 'Last name is required.',
            'last_name.min' => 'Last name must be at least 2 characters.',
            'last_name.max' => 'Last name may not be greater than 50 characters.',

            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already taken.',
            'username.alpha_dash' => 'Username may contain only letters, numbers, dashes and underscores.',
            'username.min' => 'Username must be at least 2 characters.',
            'username.max' => 'Username may not be greater than 50 characters.',

            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'An account with this email already exists.',

            'birth_date.required' => 'Date of birth is required.',
            'birth_date.date' => 'Please enter a valid date.',
            'birth_date.before' => 'You must be at least 14 years old to register.',

            'password.required' => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',
        ];
    }
}
