<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable Blade Cache
    |--------------------------------------------------------------------------
    |
    | Toggle the @cache / @endcache directive on or off globally.
    | When disabled, content inside the directives is rendered every time.
    |
    */
    'enabled' => env('BLADE_CACHE_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Default TTL (seconds)
    |--------------------------------------------------------------------------
    |
    | Time-to-live used when no explicit TTL is passed to @cache.
    |
    */
    'ttl' => env('BLADE_CACHE_TTL', 3600),

    /*
    |--------------------------------------------------------------------------
    | Cache Store
    |--------------------------------------------------------------------------
    |
    | The cache store to use. null = the application default.
    | Must support tagging (redis, memcached, array, or the
    | taggable-file driver shipped by microweber).
    |
    */
    'store' => env('BLADE_CACHE_STORE', null),

];