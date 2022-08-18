<?php return array (
  'debug' => false,
  'url' => 'http://127.0.0.1/debug/microweber-test2/',
  'timezone' => 'UTC',
  'locale' => 'en_US',
  'fallback_locale' => 'en',
  'key' => 'base64:UlZ3cEtKQU5LTkNMZjN0UWVkNVBEblVIMHlQYjZ0N2I=',
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