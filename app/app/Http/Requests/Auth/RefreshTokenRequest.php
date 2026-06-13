<?php

namespace App\Http\Requests\Auth;

use App\Enums\Messages\AuthValidationMessages;
use Illuminate\Foundation\Http\FormRequest;

class RefreshTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'refresh_token' => ['required', 'string', 'size:64'],
        ];
    }

    public function messages(): array {
        return [
            'refresh_token.required' => __(AuthValidationMessages::RefreshTokenRequired->value),
            'refresh_token.size' => __(AuthValidationMessages::RefreshTokenInvalid->value),
            'refresh_token.string' => __(AuthValidationMessages::RefreshTokenInvalid->value),
        ];
    }
}
