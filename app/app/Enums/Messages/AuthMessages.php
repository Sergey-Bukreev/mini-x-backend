<?php

namespace App\Enums\Messages;

enum AuthMessages: string
{
    case Registered = 'auth.registered';
    case InvalidCredentials = 'auth.invalid_credentials';
    case InvalidSession = 'auth.invalid_session';
    case UserNotFound = 'auth.user_not_found';
}
