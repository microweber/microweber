<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User model
    |--------------------------------------------------------------------------
    |
    | The user model used for authentication and 2FA.
    |
    */
    'user_model' => null, // null = use auth config default

    /*
    |--------------------------------------------------------------------------
    | Require 2FA options
    |--------------------------------------------------------------------------
    |
    | require2fa_all         - Force all users to set up 2FA on login
    | require2fa_admin_only  - Force only admin users to set up 2FA on login
    |
    | These can also be set as user_options via get_option('require2fa_all')
    | and get_option('require2fa_admin_only') in Microweber context.
    |
    */
    'require2fa_all' => false,
    'require2fa_admin_only' => false,

    /*
    |--------------------------------------------------------------------------
    | 2FA setup route
    |--------------------------------------------------------------------------
    |
    | The route name that users are redirected to when they need to set up 2FA.
    |
    */
    'setup_route' => '/two-factor/setup',

    /*
    |--------------------------------------------------------------------------
    | Rate limiting
    |--------------------------------------------------------------------------
    */
    'rate_limit' => [
        'max_attempts' => 5,
        'decay_minutes' => 15,
    ],

    /*
    |--------------------------------------------------------------------------
    | Recovery codes count
    |--------------------------------------------------------------------------
    */
    'recovery_codes_count' => 8,

    /*
    |--------------------------------------------------------------------------
    | QR code size
    |--------------------------------------------------------------------------
    */
    'qr_code_size' => 200,

    /*
    |--------------------------------------------------------------------------
    | Issuer name for TOTP
    |--------------------------------------------------------------------------
    */
    'issuer' => null, // null = use app.name
];