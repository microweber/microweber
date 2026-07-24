<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WebP Conversion
    |--------------------------------------------------------------------------
    */
    'webp_enabled' => env('MW_WEBP_ENABLED', env('IMAGE_OPTIMIZATION_WEBP_ENABLED', true)),
    'webp_quality' => (int) env('MW_WEBP_QUALITY', env('IMAGE_OPTIMIZATION_WEBP_QUALITY', 85)),
    'webp_cache' => env('MW_WEBP_CACHE', env('IMAGE_OPTIMIZATION_WEBP_CACHE', true)),
    'webp_cache_ttl' => (int) env('MW_WEBP_CACHE_TTL', env('IMAGE_OPTIMIZATION_WEBP_CACHE_TTL', 604800)),
    'auto_convert_uploads' => env('MW_WEBP_AUTO_CONVERT', env('IMAGE_OPTIMIZATION_AUTO_CONVERT', false)),

    /*
    |--------------------------------------------------------------------------
    | Lazy Loading
    |--------------------------------------------------------------------------
    */
    'lazy_loading_enabled' => env('MW_LAZY_LOADING_ENABLED', env('IMAGE_OPTIMIZATION_LAZY_LOADING', true)),
    'placeholder_url' => env(
        'MW_LAZY_PLACEHOLDER',
        env(
            'IMAGE_OPTIMIZATION_PLACEHOLDER',
            'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E'
        )
    ),

    /*
    |--------------------------------------------------------------------------
    | Storage / Cache
    |--------------------------------------------------------------------------
    |
    | Relative path (under the public disk) where WebP conversions are stored.
    |
    */
    'cache_path' => env('IMAGE_OPTIMIZATION_CACHE_PATH', 'cache/webp'),
    'disk' => env('IMAGE_OPTIMIZATION_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Intervention Image Driver
    |--------------------------------------------------------------------------
    */
    'driver' => env(
        'IMAGE_OPTIMIZATION_DRIVER',
        \Intervention\Image\Drivers\Gd\Driver::class
    ),

    /*
    |--------------------------------------------------------------------------
    | Route middleware
    |--------------------------------------------------------------------------
    */
    'middleware' => ['web'],
    'admin_middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Supported source formats
    |--------------------------------------------------------------------------
    */
    'supported_formats' => ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'],
];
