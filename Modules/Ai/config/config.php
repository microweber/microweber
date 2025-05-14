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

    'default_driver' => env('AI_DRIVER', 'openai'),
    'default_driver_images' => env('AI_DRIVER_IMAGES', 'gemini'),

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
            'enabled' => env('OPENAI_ENABLED', false),
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
            'max_tokens' => env('OPENAI_MAX_TOKENS', null),
            'temperature' => env('OPENAI_TEMPERATURE', 0.7),
            'use_cache' => true,
            'cache_duration' => env('OPENAI_CACHE_DURATION', 600), // minutes
            'models' => [
                'gpt-4o-mini' => 'GPT 4o Mini',
                'gpt-4.1-nano' => 'GPT 4.1 Nano',
                'gpt-4.1-mini' => 'GPT 4.1 Mini',


                'gpt-4o' => 'GPT 4o',
            ],
        ],

        'ollama' => [
            'enabled' => env('OLLAMA_ENABLED', false),
            'url' => env('OLLAMA_API_URL', 'http://localhost:11434/api/generate'),
            'model' => env('OLLAMA_MODEL', 'llama3.2'), // Specify your default model
            'models' => [
                'llama3.2' => 'Llama 3.2'
            ],
        ],

        'openrouter' => [
            'enabled' => env('OPENROUTER_ENABLED', false),
            'model' => env('OPENROUTER_MODEL', 'meta-llama/llama-3.3-70b-instruct'),
            'use_cache' => env('OPENROUTER_USE_CACHE', false),
            'cache_duration' => env('OPENROUTER_CACHE_DURATION', 600), // minutes
            'api_key' => env('OPENROUTER_API_KEY'),
            'api_endpoint' => env('OPENROUTER_API_ENDPOINT', 'https://openrouter.ai/api/v1/'),
            'api_timeout' => env('OPENROUTER_API_TIMEOUT', 200),
            'title' => env('OPENROUTER_API_TITLE', 'Microweber'),
            'referer' => env('OPENROUTER_API_REFERER', 'https://github.com/microweber/microweber'),
            'models' => [
                'meta-llama/llama-3.3-70b-instruct' => 'Meta Llama 3.3 70B Instruct',
                'meta-llama/llama-3-8b-instruct' => 'Meta Llama 3 8B Instruct',
                'anthropic/claude-3-5-sonnet' => 'Claude 3.5 Sonnet',
                'anthropic/claude-3-opus' => 'Claude 3 Opus',
            ],
        ],

        'gemini' => [
            'enabled' => env('GEMINI_ENABLED', false),
            'supports_images' => env('GEMINI_SUPPORTS_IMAGES', true),
            'api_key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_MODEL', 'gemini-pro'),
            'max_tokens' => env('GEMINI_MAX_TOKENS', null),
            'temperature' => env('GEMINI_TEMPERATURE', 0.7),
            'api_endpoint' => env('GEMINI_API_ENDPOINT', 'https://generativelanguage.googleapis.com/v1beta/models/'),
            'models' => [
                'gemini-pro' => 'Gemini Pro',
                'gemini-pro-vision' => 'Gemini Pro Vision',
                'gemini-1.5-pro' => 'Gemini 1.5 Pro',
                'gemini-1.5-flash' => 'Gemini 1.5 Flash',
                'gemini-2.0-flash-exp' => 'Gemini 2.0 Flash Experimental',
            ],
        ],
    ],
];
