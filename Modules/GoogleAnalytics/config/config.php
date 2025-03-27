<?php

return [
    'name' => 'GoogleAnalytics',
    
    /*
    |--------------------------------------------------------------------------
    | Google Analytics Configuration
    |--------------------------------------------------------------------------
    |
    | These environment variables should be set in your .env file
    |
    */
    
    'enabled' => env('GOOGLE_ANALYTICS_ENABLED', false),
    'measurement_id' => env('GOOGLE_ANALYTICS_MEASUREMENT_ID', 'G-XXXXXXXX'),
    'api_secret' => env('GOOGLE_ANALYTICS_API_SECRET', null),
    
    // Enhanced Conversions
    'enhanced_conversions' => [
        'enabled' => env('GOOGLE_ANALYTICS_ENHANCED_CONVERSIONS', false),
        'conversion_id' => env('GOOGLE_ANALYTICS_CONVERSION_ID', null),
        'conversion_label' => env('GOOGLE_ANALYTICS_CONVERSION_LABEL', null),
    ],
    
    // Debugging
    'debug' => env('GOOGLE_ANALYTICS_DEBUG', false),
];
