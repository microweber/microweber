<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | CSS files base path / URL
    |--------------------------------------------------------------------------
    |
    | Live-edit CSS and other per-template CSS files are stored under:
    |   {css_base_path}/{template}/live_edit.css
    |
    | Microweber CMS keeps this at userfiles/css/ so backup/restore of
    | templates continues to find the same paths. Standalone apps can
    | point this at storage/app/public/css.
    |
    */
    'css_base_path' => env('TEMPLATE_CUSTOM_CSS_PATH', storage_path('app/public/css')),
    'css_base_url' => env('TEMPLATE_CUSTOM_CSS_URL', '/storage/css'),

    /*
    |--------------------------------------------------------------------------
    | Cache for compiled / printed custom CSS
    |--------------------------------------------------------------------------
    */
    'css_cache_path' => env('TEMPLATE_CUSTOM_CSS_CACHE_PATH', storage_path('app/public/cache')),
    'css_cache_url' => env('TEMPLATE_CUSTOM_CSS_CACHE_URL', '/storage/cache'),
    'compile_assets' => (bool) env('TEMPLATE_CUSTOM_CSS_COMPILE_ASSETS', false),
    // No configurable css_version: the compiled filename is busted by a full-md5
    // content signature, and MW_VERSION (in the CMS) is added automatically as an
    // optional provenance segment.

    /*
    |--------------------------------------------------------------------------
    | Userfiles URL (for relative path rewriting on save)
    |--------------------------------------------------------------------------
    |
    | When saving live-edit CSS, absolute media URLs are rewritten to relative
    | paths (../../) so backups remain portable across domains. Set to the
    | public base URL of userfiles/media in your app.
    |
    */
    'userfiles_url' => env('TEMPLATE_CUSTOM_CSS_USERFILES_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Multisite
    |--------------------------------------------------------------------------
    |
    | When true, live_edit CSS is stored as live_edit_{environment}.css
    | (matching the Microweber CMS convention).
    |
    */
    'multisite' => (bool) env('TEMPLATE_CUSTOM_CSS_MULTISITE', false),
    'environment' => env('TEMPLATE_CUSTOM_CSS_ENVIRONMENT', null),

    /*
    |--------------------------------------------------------------------------
    | Default template folder name
    |--------------------------------------------------------------------------
    */
    'default_template' => env('TEMPLATE_CUSTOM_CSS_DEFAULT_TEMPLATE', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Option storage keys (CMS options table or standalone option store)
    |--------------------------------------------------------------------------
    */
    'live_edit_option_key' => 'template_css',
    'live_edit_option_group_prefix' => 'template_',
    'custom_css_option_key' => 'custom_css',
    'custom_css_option_group' => 'template',
    'template_settings_option_key' => 'template_settings',

    /*
    |--------------------------------------------------------------------------
    | Registered CSS file types
    |--------------------------------------------------------------------------
    |
    | The manager supports an arbitrary number of named CSS file slots.
    | Built-in: live_edit (per-template file) and custom (option-stored).
    | Apps may register more (e.g. per-page) at runtime via
    | TemplateCustomCssManager::registerFileType().
    |
    */
    'file_types' => [
        'live_edit' => [
            'filename' => 'live_edit.css',
            'storage' => 'file', // file | option
            'option_key' => 'template_css',
            'option_group_prefix' => 'template_',
            'multisite' => true,
            'rewrite_urls' => true,
            'validate' => true,
        ],
        'custom' => [
            'filename' => null,
            'storage' => 'option',
            'option_key' => 'custom_css',
            'option_group' => 'template',
            'multisite' => false,
            'rewrite_urls' => false,
            'validate' => true,
            'cache' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | CSS validation
    |--------------------------------------------------------------------------
    */
    'validate_on_save' => (bool) env('TEMPLATE_CUSTOM_CSS_VALIDATE', true),
    // Empty CSS is always allowed (used to clear styles).
    'allow_empty_css' => true,

    /*
    |--------------------------------------------------------------------------
    | Route middleware
    |--------------------------------------------------------------------------
    */
    'middleware' => ['web'],
    // Microweber CMS uses api+admin+csrf; standalone apps can override to ['web']
    'admin_middleware' => ['api', 'admin'],
    'csrf_middleware' => null,

    /*
    |--------------------------------------------------------------------------
    | Route prefix / names
    |--------------------------------------------------------------------------
    |
    | Keep Microweber-compatible paths by default so existing frontend JS works.
    |
    */
    'route_prefix' => 'api',
    'route_name_prefix' => '',
    'save_live_edit_route' => 'current_template_save_custom_css',
    'remove_css_route' => 'layouts/template_remove_custom_css',
    'print_custom_css_route' => 'template/print_custom_css',
    'save_custom_css_route' => 'template/save_custom_css',

    /*
    |--------------------------------------------------------------------------
    | Admin authorization callback class (optional)
    |--------------------------------------------------------------------------
    |
    | When set, the controller will call this callable / invokable class to
    | decide if the current user may save CSS. Default falls back to
    | function_exists('is_admin') then auth check.
    |
    */
    'admin_gate' => null,
];
