<?php

return [
    'access' => [
        'name' => env('ACCESS_TOKEN_NAME', 'auth_token'),
        'expires_in_minutes' => env('ACCESS_TOKEN_EXPIRES_IN_MINUTES', 15),
    ],

    'refresh' => [
        'expires_in_days' => env('REFRESH_TOKEN_EXPIRES_IN_DAYS', 7),
        'absolute_expires_in_days' => env('REFRESH_TOKEN_ABSOLUTE_EXPIRES_IN_DAYS', 90),
    ],
];
