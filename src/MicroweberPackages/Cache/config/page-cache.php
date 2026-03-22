<?php

declare(strict_types=1);

/**
 * Page Cache Configuration
 * 
 * This configuration file controls the full-page caching system.
 * 
 * @package MicroweberPackages\Cache
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Enable Page Cache
    |--------------------------------------------------------------------------
    |
    | This option enables or disables the full-page caching system.
    | When enabled, entire HTML pages will be cached and served from
    | cache on subsequent requests.
    |
    */
    'enabled' => env('PAGE_CACHE_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Cache TTL (Time To Live)
    |--------------------------------------------------------------------------
    |
    | The default cache lifetime in seconds. Pages will be cached for
    | this duration before being regenerated.
    |
    */
    'ttl' => env('PAGE_CACHE_TTL', 3600),

    /*
    |--------------------------------------------------------------------------
    | Cache Driver
    |--------------------------------------------------------------------------
    |
    | The cache driver to use for page caching. Must support tagging
    | (redis, memcached, or array). Uses the default cache driver
    | if not specified.
    |
    */
    'driver' => env('PAGE_CACHE_DRIVER', config('cache.default')),

    /*
    |--------------------------------------------------------------------------
    | Cache for Logged-in Users
    |--------------------------------------------------------------------------
    |
    | Whether to cache pages for authenticated users. This can
    | improve performance for logged-in users but may show
    | personalized content incorrectly if not handled properly.
    |
    */
    'cache_for_logged_in' => env('PAGE_CACHE_LOGGED_IN', false),

    /*
    |--------------------------------------------------------------------------
    | Cache with Query Parameters
    |--------------------------------------------------------------------------
    |
    | Whether to cache pages with query parameters. If disabled,
    | only the base URL will be cached. If enabled, different
    | query strings will result in different cached versions.
    |
    */
    'cache_with_query_params' => env('PAGE_CACHE_QUERY_PARAMS', false),

    /*
    |--------------------------------------------------------------------------
    | Excluded URL Patterns
    |--------------------------------------------------------------------------
    |
    | Regular expression patterns for URLs that should not be cached.
    | These patterns will be matched against the request URI.
    |
    */
    'excluded_patterns' => [
        // Admin URLs
        '^/admin',
        '^/api',
        '^/login',
        '^/logout',
        '^/register',
        '^/password',
        
        // User-specific URLs
        '^/profile',
        '^/account',
        '^/checkout',
        '^/cart',
        
        // Dynamic content
        '^/search',
        '.*preview.*',
        '.*editmode.*',
    ],

    /*
    |--------------------------------------------------------------------------
    | Mobile/Tablet Detection
    |--------------------------------------------------------------------------
    |
    | Whether to generate separate cached versions for mobile
    | and desktop users. This ensures mobile-optimized content
    | is served to mobile users.
    |
    */
    'separate_mobile_cache' => env('PAGE_CACHE_MOBILE', true),

    /*
    |--------------------------------------------------------------------------
    | Cache Key Components
    |--------------------------------------------------------------------------
    |
    | The components that make up the cache key. Changing these
    | will result in different cache keys being generated.
    |
    */
    'key_components' => [
        'url',
        'method',
        'locale',
        'mobile',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Warming Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for the cache warming feature that pre-populates
    | the cache with frequently accessed pages.
    |
    */
    'warming' => [
        'enabled' => env('PAGE_CACHE_WARMING', false),
        'chunk_size' => 10,
        'timeout' => 30,
        'concurrent' => true,
        
        // URLs to warm on schedule
        'scheduled_urls' => [
            // '/',
            // '/about',
            // '/products',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Headers
    |--------------------------------------------------------------------------
    |
    | HTTP headers to add to cached responses.
    |
    */
    'headers' => [
        'X-Page-Cache' => 'HIT',
    ],
];
