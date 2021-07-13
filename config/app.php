<?php return array (
  'debug' => false,
  'url' => 'http://localhost/',
  'timezone' => 'UTC',
  'locale' => 'en_US',
  'fallback_locale' => 'en',
  'key' => 'base64:Mk1lT2dFUzJSWmZJc0RMZEYyQ3V5SElNMXltVE9HTUM=',
  'cipher' => 'AES-256-CBC',
  'log' => 'daily',
  'providers' => 
  array (
    0 => 'MicroweberPackages\\App\\Providers\\AppServiceProvider',
    1 => 'MicroweberPackages\\App\\Providers\\EventServiceProvider',
    2 => 'MicroweberPackages\\App\\Providers\\RouteServiceProvider',
  ),
  'manifest' => storage_path().DIRECTORY_SEPARATOR.'framework',
);