<?php

declare(strict_types=1);

/**
 * Fragment Cache Configuration
 * 
 * This configuration file controls the fragment/partials caching system.
 * 
 * @package MicroweberPackages\Cache
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Enable Fragment Cache
    |--------------------------------------------------------------------------
    |
    | This option enables or disables the fragment caching system.
    | When enabled, specific sections of views can be cached
    | independently of the full page.
    |
    */
    'enabled' => env('FRAGMENT_CACHE_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Default Cache TTL (Time To Live)
    |--------------------------------------------------------------------------
    |
    | The default cache lifetime in seconds for fragments.
    | Individual fragments can override this value.
    |
    */
    'ttl' => env('FRAGMENT_CACHE_TTL', 3600),

    /*
    |--------------------------------------------------------------------------
    | Cache Driver
    |--------------------------------------------------------------------------
    |
    | The cache driver to use for fragment caching. Must support tagging
    | (redis, memcached, or array). Uses the default cache driver
    | if not specified.
    |
    */
    'driver' => env('FRAGMENT_CACHE_DRIVER', config('cache.default')),

    /*
    |--------------------------------------------------------------------------
    | Default Tags
    |--------------------------------------------------------------------------
    |
    | Default tags that will be applied to all cached fragments.
    | These tags can be used for bulk invalidation.
    |
    */
    'default_tags' => ['fragment'],

    /*
    |--------------------------------------------------------------------------
    | Fragment Types
    |--------------------------------------------------------------------------
    |
    | Predefined fragment types with their default settings.
    | These can be used as shortcuts when caching fragments.
    |
    */
    'types' => [
        'menu' => [
            'ttl' => 7200, // 2 hours
            'tags' => ['menu', 'navigation'],
        ],
        'category' => [
            'ttl' => 3600, // 1 hour
            'tags' => ['category', 'taxonomy'],
        ],
        'product_list' => [
            'ttl' => 1800, // 30 minutes
            'tags' => ['product', 'catalog'],
        ],
        'widget' => [
            'ttl' => 3600,
            'tags' => ['widget'],
        ],
        'module' => [
            'ttl' => 3600,
            'tags' => ['module'],
        ],
        'sidebar' => [
            'ttl' => 3600,
            'tags' => ['sidebar'],
        ],
        'footer' => [
            'ttl' => 7200,
            'tags' => ['footer'],
        ],
        'header' => [
            'ttl' => 7200,
            'tags' => ['header'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto-Cache Blade Directives
    |--------------------------------------------------------------------------
    |
    | Blade directives that should automatically cache their content
    | when used in templates.
    |
    */
    'auto_cache_directives' => [
        // '@menu' => ['ttl' => 7200, 'tags' => ['menu']],
        // '@categories' => ['ttl' => 3600, 'tags' => ['category']],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Invalidation Events
    |--------------------------------------------------------------------------
    |
    | Model events that should trigger automatic cache invalidation
    | for specific fragment types.
    |
    */
    'invalidation_events' => [
        'content.saved' => ['content', 'page', 'post'],
        'content.deleted' => ['content', 'page', 'post'],
        'menu.saved' => ['menu', 'navigation'],
        'category.saved' => ['category', 'taxonomy'],
        'product.saved' => ['product', 'catalog'],
    ],
];
