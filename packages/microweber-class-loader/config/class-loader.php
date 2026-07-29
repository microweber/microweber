<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Class Loader
    |--------------------------------------------------------------------------
    |
    | Instance-based autoloader used by Microweber modules and reusable in
    | standalone Laravel apps. Paths are normalized so the same directory
    | registered with different separators or trailing slashes is only kept once.
    |
    */

    'enabled' => env('MW_CLASS_LOADER_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Preload directories
    |--------------------------------------------------------------------------
    |
    | Absolute paths (or paths relative to the application base path) that will
    | be registered when the service boots. Empty by default so consumers opt in.
    |
    */
    'directories' => [],

    /*
    |--------------------------------------------------------------------------
    | Preload PSR-4 namespaces
    |--------------------------------------------------------------------------
    |
    | Map of "Namespace\\" => "path/to/src" pairs registered on boot.
    |
    */
    'namespaces' => [],

    /*
    |--------------------------------------------------------------------------
    | Cache resolved classes
    |--------------------------------------------------------------------------
    |
    | Remember found/not-found class paths for faster subsequent lookups.
    | Call ClassLoader::clearCache() (or the admin self-test) to free memory.
    |
    */
    'cache_lookups' => env('MW_CLASS_LOADER_CACHE', true),
];
