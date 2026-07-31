<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Storage
    |--------------------------------------------------------------------------
    |
    | Where downloaded Google Fonts and uploaded custom fonts are stored.
    | Paths may be absolute. URL is used in generated @import / @font-face CSS.
    |
    */
    'fonts_path' => env('TEMPLATE_FONTS_PATH', storage_path('app/public/fonts')),
    'fonts_url' => env('TEMPLATE_FONTS_URL', '/storage/fonts'),

    /*
    |--------------------------------------------------------------------------
    | Google Fonts provider
    |--------------------------------------------------------------------------
    */
    'google_fonts_domain' => env('TEMPLATE_FONTS_GOOGLE_DOMAIN', 'fonts.googleapis.com'),
    'google_fonts_proxy_domain' => env('TEMPLATE_FONTS_GOOGLE_PROXY', 'google-fonts.microweberapi.com'),
    'use_google_fonts_proxy' => (bool) env('TEMPLATE_FONTS_USE_PROXY', false),
    'download_google_fonts_locally' => (bool) env('TEMPLATE_FONTS_DOWNLOAD_LOCAL', true),

    /*
    |--------------------------------------------------------------------------
    | Default system fonts always available in the picker
    |--------------------------------------------------------------------------
    */
    'system_fonts' => [
        'Arial, Helvetica, sans-serif',
        'Georgia, serif',
        'Times New Roman, serif',
        'Courier New, monospace',
        'Verdana, sans-serif',
        'Tahoma, sans-serif',
        'Trebuchet MS, sans-serif',
        'Impact, Charcoal, sans-serif',
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom font uploads
    |--------------------------------------------------------------------------
    */
    'allowed_extensions' => ['ttf', 'woff', 'woff2', 'otf'],
    'max_upload_kb' => (int) env('TEMPLATE_FONTS_MAX_UPLOAD_KB', 5120),

    /*
    |--------------------------------------------------------------------------
    | Catalog files (Google Fonts list for picker)
    |--------------------------------------------------------------------------
    */
    'catalog_path' => null, // defaults to package resources/data

    /*
    |--------------------------------------------------------------------------
    | CSS cache
    |--------------------------------------------------------------------------
    */
    'css_cache_path' => env('TEMPLATE_FONTS_CSS_CACHE_PATH', storage_path('app/public/cache')),
    'css_cache_url' => env('TEMPLATE_FONTS_CSS_CACHE_URL', '/storage/cache'),
    'compile_assets' => (bool) env('TEMPLATE_FONTS_COMPILE_ASSETS', false),
    // No configurable css_version: the compiled filename is busted by a full-md5
    // content signature, and MW_VERSION (in the CMS) is added automatically as an
    // optional provenance prefix.

    /*
    |--------------------------------------------------------------------------
    | Route middleware
    |--------------------------------------------------------------------------
    */
    'middleware' => ['web'],
    // Microweber CMS uses api+admin; standalone apps can override to ['web']
    'admin_middleware' => ['api', 'admin'],

    /*
    |--------------------------------------------------------------------------
    | Route prefix / names
    |--------------------------------------------------------------------------
    |
    | Keep Microweber-compatible paths by default so existing frontend JS works.
    |
    */
    'route_prefix' => 'api/template',
    'route_name_prefix' => 'api.template.',
    'public_css_route' => 'api/template/print_custom_css_fonts',

    /*
    |--------------------------------------------------------------------------
    | Migrate legacy options on boot
    |--------------------------------------------------------------------------
    */
    'migrate_legacy_options' => true,
    'legacy_option_key' => 'enabled_custom_fonts',
    'legacy_option_group' => 'template',
    'legacy_proxy_option_key' => 'use_google_fonts_proxy',
];
