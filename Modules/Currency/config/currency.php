<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Multi-Currency Support
    |--------------------------------------------------------------------------
    |
    | Enable or disable multi-currency support. When enabled, users can
    | switch between different currencies. When disabled, only the
    | default currency is used.
    |
    */
    'multi_currency_enabled' => env('MULTI_CURRENCY_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | The default currency code to use when no currency is selected
    | or when multi-currency is disabled. This should match a currency
    | code in your currencies table.
    |
    */
    'default_currency' => env('DEFAULT_CURRENCY', 'USD'),

    /*
    |--------------------------------------------------------------------------
    | Currency Auto-Detection
    |--------------------------------------------------------------------------
    |
    | Enable auto-detection of currency based on user location or browser
    | settings. This only works when multi-currency is enabled.
    |
    */
    'auto_detect_currency' => env('AUTO_DETECT_CURRENCY', true),

    /*
    |--------------------------------------------------------------------------
    | Exchange Rate API
    |--------------------------------------------------------------------------
    |
    | Configuration for external exchange rate APIs. You can use services
    | like exchangerate-api.com, fixer.io, or similar.
    |
    */
    'exchange_rate_api' => [
        'enabled' => env('EXCHANGE_RATE_API_ENABLED', false),
        'provider' => env('EXCHANGE_RATE_API_PROVIDER', 'exchangerate-api'),
        'api_key' => env('EXCHANGE_RATE_API_KEY', null),
        'base_currency' => env('EXCHANGE_RATE_BASE_CURRENCY', 'USD'),
        'update_interval' => env('EXCHANGE_RATE_UPDATE_INTERVAL', 3600), // seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    |
    | Configure caching for exchange rates and currency data to improve
    | performance.
    |
    */
    'cache' => [
        'enabled' => true,
        'ttl' => env('CURRENCY_CACHE_TTL', 3600), // 1 hour
        'prefix' => 'currency_',
    ],

    /*
    |--------------------------------------------------------------------------
    | Decimal Precision
    |--------------------------------------------------------------------------
    |
    | Default decimal precision for currency calculations and display.
    |
    */
    'precision' => 2,

    /*
    |--------------------------------------------------------------------------
    | Currency Display
    |--------------------------------------------------------------------------
    |
    | How to display currency amounts in the UI.
    |
    */
    'display' => [
        'show_both_currencies' => false, // Show original and converted
        'show_exchange_rate' => false, // Show exchange rate info
        'decimal_places' => 2,
    ],

    /*
    |--------------------------------------------------------------------------
    | Supported API Providers
    |--------------------------------------------------------------------------
    |
    | Configuration for supported exchange rate API providers.
    |
    */
    'providers' => [
        'exchangerate-api' => [
            'url' => 'https://api.exchangerate-api.com/v4/latest/',
            'free_tier' => true,
        ],
        'fixer' => [
            'url' => 'https://api.fixer.io/latest',
            'free_tier' => false,
        ],
        'open-exchange-rates' => [
            'url' => 'https://openexchangerates.org/api/latest.json',
            'free_tier' => true,
        ],
    ],
];
