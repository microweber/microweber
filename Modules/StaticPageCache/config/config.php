<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable Static Page Cache
    |--------------------------------------------------------------------------
    |
    | This can also be toggled from the admin panel Settings page.
    | The admin panel setting (option "enable_full_page_cache") takes
    | precedence over this config value.
    |
    */
    'enabled' => env('STATIC_PAGE_CACHE_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Cache TTL (seconds)
    |--------------------------------------------------------------------------
    */
    'ttl' => env('STATIC_PAGE_CACHE_TTL', 3600),

    /*
    |--------------------------------------------------------------------------
    | Cache for logged-in users
    |--------------------------------------------------------------------------
    */
    'cache_for_logged_in' => env('STATIC_PAGE_CACHE_LOGGED_IN', false),

    /*
    |--------------------------------------------------------------------------
    | Excluded URL Patterns
    |--------------------------------------------------------------------------
    */
    'excluded_patterns' => [
        '^/admin',
        '^/api',
        '^/login',
        '^/logout',
        '^/register',
        '^/password',
        '^/profile',
        '^/account',
        '^/checkout',
        '^/cart',
        '^/search',
        '.*preview.*',
        '.*editmode.*',
    ],

];