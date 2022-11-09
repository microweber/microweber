<?php return array (
  'name' => 'Laravel',
  'env' => 'production',
  'debug' => 1,
  'url' => 'http://127.0.0.1:8000/',
  'asset_url' => NULL,
  'timezone' => 'UTC',
  'locale' => 'en_US',
  'fallback_locale' => 'en',
  'key' => 'base64:UWxpV2w2dFdORWVUaVQ1SHdmTFVzbzNkY3pNMnFUTXk=',
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
