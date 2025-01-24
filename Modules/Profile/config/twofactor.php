<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authentication Settings
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for two-factor authentication.
    |
    */

    'enabled' => env('TWO_FACTOR_ENABLED', true),
    
    // Time window for TOTP codes (in seconds)
    'window' => 30,
    
    // Number of recovery codes to generate
    'recovery_codes' => 8,
    
    // Rate limiting settings
    'rate_limit' => [
        'max_attempts' => 5,
        'decay_minutes' => 15,
    ],
    
    // QR Code settings
    'qr_code' => [
        'size' => 200,
        'margin' => 4,
    ],
    
    // Session settings
    'session' => [
        'key' => '2fa_verified',
        'lifetime' => 3600, // 1 hour
    ],
    
    // Notification channels
    'notification_channels' => [
        'mail',
        'sms' => env('TWO_FACTOR_SMS_ENABLED', false),
    ],
];