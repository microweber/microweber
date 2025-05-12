<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default AI Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default AI service that will be used. You may
    | set this to any of the drivers defined in the "drivers" array below.
    |
    */

    'default' => env('AI_DRIVER', 'openai'),

    /*
    |--------------------------------------------------------------------------
    | AI Drivers
    |--------------------------------------------------------------------------
    |
    | Here you may configure the AI drivers for your application. Each driver
    | can have its own configuration options that are specific to that service.
    |
    */

    'drivers' => [
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
            'max_tokens' => env('OPENAI_MAX_TOKENS', 1000),
            'temperature' => env('OPENAI_TEMPERATURE', 0.7),
        ],

        'ollama' => [
            'url' => env('OLLAMA_API_URL', 'http://localhost:11434/api/generate'),
            'model' => env('OLLAMA_MODEL', 'llama3.2'), // Specify your default model
        ],

        'openrouter' => [
            'model' => env('OPENROUTER_MODEL', 'meta-llama/llama-3.3-70b-instruct'),
            'use_cache' => env('OPENROUTER_USE_CACHE', false),
            'cache_duration' => env('OPENROUTER_CACHE_DURATION', 600), // minutes
            'api_key' => env('OPENROUTER_API_KEY'),
            'api_endpoint' => env('OPENROUTER_API_ENDPOINT', 'https://openrouter.ai/api/v1/'),
            'api_timeout' => env('OPENROUTER_API_TIMEOUT', 200),
            'title' => env('OPENROUTER_API_TITLE', 'Microweber'),
            'referer' => env('OPENROUTER_API_REFERER', 'https://github.com/microweber/microweber'),
        ],
    ],


];
