<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default AI Service Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default AI service that will be used to generate
    | content. You may set this to any of the drivers defined in the
    | "drivers" configuration array below.
    |
    */
    'default' => env('AI_DRIVER', 'openai'),

    /*
    |--------------------------------------------------------------------------
    | AI Service Drivers
    |--------------------------------------------------------------------------
    |
    | Here you may configure the settings for each AI service driver. Each driver
    | may have its own specific configuration options.
    |
    */
    'drivers' => [
        'openai' => [
            'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
            'max_tokens' => env('OPENAI_MAX_TOKENS', 1000),
            'temperature' => env('OPENAI_TEMPERATURE', 0.7),
        ],
        
        // Add other drivers here as needed, e.g.:
        // 'openrouter' => [
        //     'api_key' => env('OPENROUTER_API_KEY'),
        //     'model' => env('OPENROUTER_MODEL'),
        //     'max_tokens' => env('OPENROUTER_MAX_TOKENS', 1000),
        //     'temperature' => env('OPENROUTER_TEMPERATURE', 0.7),
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Page Generation Settings
    |--------------------------------------------------------------------------
    |
    | These settings control how the AI generates Microweber pages.
    |
    */
    'page_generation' => [
        'system_prompt' => 'You are a professional web content creator specializing in creating engaging and SEO-friendly website content.',
        'default_sections' => [
            'header',
            'content',
            'features',
            'testimonials',
            'contact',
        ],
    ],
];
