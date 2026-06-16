<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Template
    |--------------------------------------------------------------------------
    |
    | This value specifies the default template to be installed.
    |
    */
    'install_default_template' => env('MW_INSTALL_DEFAULT_TEMPLATE', 'Bootstrap'),

    /*
    |--------------------------------------------------------------------------
    | Install Default Template Content
    |--------------------------------------------------------------------------
    |
    | This value determines whether to install the default template content.
    |
    */
    'install_default_template_content' => env('MW_INSTALL_DEFAULT_TEMPLATE_CONTENT', true),

    /*
    |--------------------------------------------------------------------------
    | Compile Assets
    |--------------------------------------------------------------------------
    |
    | This value determines whether to compile assets.
    |
    */
    'compile_assets' => env('MW_COMPILE_ASSETS', true),

    /*
    |--------------------------------------------------------------------------
    | Disable Models Cache
    |--------------------------------------------------------------------------
    |
    | This value determines whether to disable the model cache.
    |
    */
    'disable_model_cache' => env('MW_DISABLE_MODEL_CACHE', false),

    /*
    |--------------------------------------------------------------------------
    | Admin URL
    |--------------------------------------------------------------------------
    |
    | This value specifies the admin URL.
    |
    */
    'admin_url' => env('MW_ADMIN_URL', 'admin'),

    /*
    |--------------------------------------------------------------------------
    | Site Language
    |--------------------------------------------------------------------------
    |
    | This value specifies the site language.
    |
    */
    'site_lang' => env('MW_SITE_LANG', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Is Installed
    |--------------------------------------------------------------------------
    |
    | This value checks if the site is installed.
    | After installation, this value will be set to true and saved in .env file.
    |
    */
    'is_installed' => env('MW_IS_INSTALLED', false),


    /*
    |--------------------------------------------------------------------------
    | Force HTTPS
    |--------------------------------------------------------------------------
    |
    | This value determines whether to force HTTPS.
    |
    */
    'force_https' => env('MW_FORCE_HTTPS', is_https()),

    /*
     * The ParserHelpers\LayoutProcessor pipeline (quote-aware TagLexer +
     * AttributeParser + ModuleIdAllocator + ContentProtector + ModuleRenderer +
     * edit-field loading + inherit resolution) is THE DEFAULT parser — validated
     * at parity (406/406 Big layouts, homepage + stress + nested-edit + inherited
     * pages, unit + integration + Dusk).
     *
     * To fall back to the legacy phpQuery parser, opt in with ANY of:
     *   - this config / env `MW_USE_LEGACY_PARSER=true`,
     *   - the admin option `use_legacy_parser` (group "website"),
     *   - the per-request override `?use_legacy_parser=1` (admin / app.debug only).
     */
    'use_legacy_parser' => env('MW_USE_LEGACY_PARSER', false),

];
