<?php

namespace App\Enums\Messages;

enum AuthValidationMessages: string
{
    case RegisterFirstNameRequired = 'auth_validation.register.first_name.required';
    case RegisterFirstNameMin = 'auth_validation.register.first_name.min';
    case RegisterFirstNameMax = 'auth_validation.register.first_name.max';

    case RegisterLastNameRequired = 'auth_validation.register.last_name.required';
    case RegisterLastNameMin = 'auth_validation.register.last_name.min';
    case RegisterLastNameMax = 'auth_validation.register.last_name.max';

    case RegisterUsernameRequired = 'auth_validation.register.username.required';
    case RegisterUsernameUnique = 'auth_validation.register.username.unique';
    case RegisterUsernameAlphaDash = 'auth_validation.register.username.alpha_dash';
    case RegisterUsernameMin = 'auth_validation.register.username.min';
    case RegisterUsernameMax = 'auth_validation.register.username.max';

    case RegisterEmailRequired = 'auth_validation.register.email.required';
    case RegisterEmailInvalid = 'auth_validation.register.email.email';
    case RegisterEmailUnique = 'auth_validation.register.email.unique';

    case RegisterBirthDateRequired = 'auth_validation.register.birth_date.required';
    case RegisterBirthDateInvalid = 'auth_validation.register.birth_date.date';
    case RegisterBirthDateBefore = 'auth_validation.register.birth_date.before';

    case RegisterPasswordRequired = 'auth_validation.register.password.required';
    case RegisterPasswordConfirmed = 'auth_validation.register.password.confirmed';

    case RefreshTokenRequired = 'auth_validation.refresh.refresh_token.required';
    case RefreshTokenInvalid = 'auth_validation.refresh.refresh_token.invalid';
}
