<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    |
    | Master switch. When false the "not_disposable_email" validation rule and
    | the block_disposable_email middleware short-circuit and treat every email
    | as allowed (the DisposableEmailChecker service itself is flag-agnostic —
    | it always answers from the domain list). Toggle via an admin panel or .env.
    |
    */
    'enabled' => (bool) env('DISPOSABLE_EMAIL_CHECKER_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Domain list path
    |--------------------------------------------------------------------------
    |
    | Absolute path to a newline-separated list of disposable email domains.
    | Defaults to the list shipped with this package.
    |
    */
    'list_path' => env(
        'DISPOSABLE_EMAIL_CHECKER_LIST',
        __DIR__ . '/../resources/data/disposable_email_addresses.txt'
    ),

];