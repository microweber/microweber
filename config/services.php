<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OAuth Service Configuration
    |--------------------------------------------------------------------------
    |
    | This file configures OAuth providers for social authentication.
    | These values are dynamically set at runtime from database options.
    | See UserSocialiteServiceProvider for dynamic configuration.
    |
    */

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', '/oauth/callback/google'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI', '/oauth/callback/facebook'),
    ],

    'twitter' => [
        'client_id' => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect' => env('TWITTER_REDIRECT_URI', '/oauth/callback/twitter'),
    ],

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_REDIRECT_URI', '/oauth/callback/github'),
    ],

    'linkedin' => [
        'client_id' => env('LINKEDIN_CLIENT_ID'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
        'redirect' => env('LINKEDIN_REDIRECT_URI', '/oauth/callback/linkedin'),
    ],

'microweber' => [
'client_id' => env('MICROWEBER_CLIENT_ID'),
'client_secret' => env('MICROWEBER_CLIENT_SECRET'),
'redirect' => env('MICROWEBER_REDIRECT_URI', '/oauth/callback/microweber'),
],

/*
|--------------------------------------------------------------------------
| Payment Service Configuration
|--------------------------------------------------------------------------
|
| This section configures payment gateway services including
| Stripe for payment processing and webhooks.
|
*/

'stripe' => [
'publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
'secret_key' => env('STRIPE_SECRET_KEY'),
'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
],
];
