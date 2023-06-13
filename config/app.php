<?php return array(
    'name' => 'Laravel',
    'env' => 'production',
    'debug' => 1,
    'url' => 'https://templates.microweber.com/mw/',
    'asset_url' => NULL,
    'timezone' => 'UTC',
    'locale' => 'en_US',
    'fallback_locale' => 'en',
    'key' => 'base64:RHd1TUNkQXBCRUt1UG9oU3lEVUJUY1NlaEJQVTVuQ1E=',
    'cipher' => 'AES-256-CBC',
    'log' => 'daily',
    'providers' =>
        array(
            0 => 'MicroweberPackages\\App\\Providers\\AppServiceProvider',
            1 => 'MicroweberPackages\\App\\Providers\\EventServiceProvider',
            2 => 'MicroweberPackages\\App\\Providers\\RouteServiceProvider',
        ),
    'manifest' => storage_path() . DIRECTORY_SEPARATOR . 'framework',
);
