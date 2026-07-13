<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Token Expiration
    |--------------------------------------------------------------------------
    */

    'tokens_expire_days' => env('MW_PASSPORT_TOKEN_EXPIRE_DAYS', 15),

    'refresh_tokens_expire_days' => env('MW_PASSPORT_REFRESH_TOKEN_EXPIRE_DAYS', 30),

    'personal_access_tokens_expire_days' => env('MW_PASSPORT_PAT_EXPIRE_DAYS', 365),

    /*
    |--------------------------------------------------------------------------
    | Default Scopes
    |--------------------------------------------------------------------------
    */

    'default_scope' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    |
    | Define all available Passport scopes. Each module gets a :read and
    | :write pair. Override this array entirely in your app config to
    | customise which scopes are offered.
    |
    */

    'scopes' => [
        'content:read'    => 'Read Content',
        'content:write'   => 'Create, update and delete Content',
        'pages:read'      => 'Read Pages',
        'pages:write'     => 'Create, update and delete Pages',
        'posts:read'      => 'Read Posts',
        'posts:write'     => 'Create, update and delete Posts',
        'tags:read'       => 'Read Tags',
        'tags:write'      => 'Create, update and delete Tags',
        'comments:read'   => 'Read Comments',
        'comments:write'  => 'Create, update and delete Comments',
        'menus:read'      => 'Read Menus',
        'menus:write'     => 'Create, update and delete Menus',
        'media:read'      => 'Read Media files',
        'media:write'     => 'Create, update and delete Media files',
        'forms:read'      => 'Read Contact forms',
        'forms:write'     => 'Create, update and delete Contact forms',
        'products:read'   => 'Read Products',
        'products:write'  => 'Create, update and delete Products',
        'categories:read' => 'Read Categories',
        'categories:write'=> 'Create, update and delete Categories',
        'orders:read'     => 'Read Orders',
        'orders:write'    => 'Create, update and delete Orders',
        'coupons:read'    => 'Read Coupons',
        'coupons:write'   => 'Create, update and delete Coupons',
        'shipping:read'   => 'Read Shipping options',
        'shipping:write'  => 'Create, update and delete Shipping options',
        'tax:read'        => 'Read Tax rules',
        'tax:write'       => 'Create, update and delete Tax rules',
        'invoices:read'   => 'Read Invoices',
        'invoices:write'  => 'Create, update and delete Invoices',
        'users:read'      => 'Read Users',
        'users:write'     => 'Create, update and delete Users',
        'customers:read'  => 'Read Customers',
        'customers:write' => 'Create, update and delete Customers',
        'profile:read'    => 'Read The authenticated user profile',
        'profile:write'   => 'Create, update and delete The authenticated user profile',
        'newsletter:read' => 'Read Newsletter subscribers',
        'newsletter:write'=> 'Create, update and delete Newsletter subscribers',
        'settings:read'   => 'Read Site settings',
        'settings:write'  => 'Create, update and delete Site settings',
    ],

    /*
    |--------------------------------------------------------------------------
    | Token Usage Stamp Interval
    |--------------------------------------------------------------------------
    |
    | Seconds between DB writes when stamping token last-used-at. Higher
    | values reduce database writes on high-traffic tokens.
    |
    */

    'token_usage_stamp_interval_seconds' => env('MW_PASSPORT_STAMP_INTERVAL', 60),

    /*
    |--------------------------------------------------------------------------
    | Per-Token Rate Limit
    |--------------------------------------------------------------------------
    */

    'per_token_rate_limit_per_minute' => env('MW_PASSPORT_RATE_LIMIT', 120),
];