<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Package repository servers (Satis / Composer packages.json endpoints)
    |--------------------------------------------------------------------------
    */
    'package_servers' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('PACKAGE_MANAGER_SERVERS', 'https://modules.microweberapi.com/packages.json'))
    ))),

    /*
    |--------------------------------------------------------------------------
    | Install base directories (absolute or relative to base_path)
    |--------------------------------------------------------------------------
    | Overridable so the package works in Microweber CMS and standalone Laravel.
    */
    'modules_path' => env('PACKAGE_MANAGER_MODULES_PATH', 'Modules'),
    'templates_path' => env('PACKAGE_MANAGER_TEMPLATES_PATH', 'Templates'),
    'vendor_path' => env('PACKAGE_MANAGER_VENDOR_PATH', 'vendor'),

    /*
    |--------------------------------------------------------------------------
    | Temporary download / extract location
    |--------------------------------------------------------------------------
    */
    'download_path' => env('PACKAGE_MANAGER_DOWNLOAD_PATH', 'storage/cache/composer-download'),

    /*
    |--------------------------------------------------------------------------
    | Install log file (relative to base_path, or absolute)
    |--------------------------------------------------------------------------
    */
    'log_path' => env('PACKAGE_MANAGER_LOG_PATH', 'storage/logs/package-install.log'),

    /*
    |--------------------------------------------------------------------------
    | Cache store key prefix for confirm-install tokens
    |--------------------------------------------------------------------------
    */
    'cache_store' => env('PACKAGE_MANAGER_CACHE_STORE', null),
    'cache_ttl_seconds' => (int) env('PACKAGE_MANAGER_CACHE_TTL', 3600),

    /*
    |--------------------------------------------------------------------------
    | HTTP client options
    |--------------------------------------------------------------------------
    */
    'http' => [
        'timeout' => (int) env('PACKAGE_MANAGER_HTTP_TIMEOUT', 30),
        'connect_timeout' => (int) env('PACKAGE_MANAGER_HTTP_CONNECT_TIMEOUT', 10),
        'verify_ssl' => (bool) env('PACKAGE_MANAGER_VERIFY_SSL', true),
        'user_agent' => env('PACKAGE_MANAGER_USER_AGENT', 'MicroweberPackageManagerClient/1.0'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Package type → relative base directory map
    |--------------------------------------------------------------------------
    */
    'type_paths' => [
        'microweber-module' => 'modules',
        'microweber-template' => 'templates',
        'laravel-module' => 'modules',
        'nwidart-module' => 'modules',
        'library' => 'vendor',
    ],
];
