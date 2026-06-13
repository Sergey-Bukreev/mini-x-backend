<?php

return [
    'register' => [
        'first_name' => [
            'required' => 'First name is required.',
            'min' => 'First name must be at least 2 characters.',
            'max' => 'First name may not be greater than 50 characters.',
        ],

        'last_name' => [
            'required' => 'Last name is required.',
            'min' => 'Last name must be at least 2 characters.',
            'max' => 'Last name may not be greater than 50 characters.',
        ],

        'username' => [
            'required' => 'Username is required.',
            'unique' => 'This username is already taken.',
            'alpha_dash' => 'Username may contain only letters, numbers, dashes and underscores.',
            'min' => 'Username must be at least 2 characters.',
            'max' => 'Username may not be greater than 50 characters.',
        ],

        'email' => [
            'required' => 'Email address is required.',
            'email' => 'Please enter a valid email address.',
            'unique' => 'An account with this email already exists.',
        ],

        'birth_date' => [
            'required' => 'Date of birth is required.',
            'date' => 'Please enter a valid date.',
            'before' => 'You must be at least 14 years old to register.',
        ],

        'password' => [
            'required' => 'Password is required.',
            'confirmed' => 'Passwords do not match.',
        ],
    ],

    'refresh' => [
        'refresh_token' => [
            'required' => 'Refresh token is required.',
            'invalid' => 'Refresh token is invalid.',
        ]
    ]
];
