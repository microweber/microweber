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
    'default_driver_images' => env('AI_DRIVER_IMAGES', 'replicate'),

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
            'url' => env('OLLAMA_API_URL', 'http://localhost:11434/api'),
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
            'api_key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_MODEL', 'gemini-2.0-flash'),
            'max_tokens' => env('GEMINI_MAX_TOKENS', null),
            'temperature' => env('GEMINI_TEMPERATURE', 0.7),
            'use_cache' => env('GEMINI_USE_CACHE', false),
            'cache_duration' => env('GEMINI_CACHE_DURATION', 600), // minutes
            'api_endpoint' => env('GEMINI_API_ENDPOINT', 'https://generativelanguage.googleapis.com/v1beta'),
            'models' => [
                'gemini-2.0-flash' => 'Gemini 2.0 Flash',
                'gemini-1.0-pro' => 'Gemini 1.0 Pro',
                'gemini-1.0-pro-vision' => 'Gemini 1.0 Pro Vision',
                'gemini-1.5-pro' => 'Gemini 1.5 Pro',
                'gemini-1.5-pro-vision' => 'Gemini 1.5 Pro Vision',
                'gemini-1.5-flash' => 'Gemini 1.5 Flash',

            ],
        ],


        'replicate' => [
            'enabled' => env('REPLICATE_ENABLED', false),
            'api_key' => env('REPLICATE_API_KEY'),
            'model' => env('REPLICATE_MODEL', 'google/imagen-3'),
            'max_tokens' => env('REPLICATE_MAX_TOKENS', null),
            'temperature' => env('REPLICATE_TEMPERATURE', 0.7),
            'use_cache' => env('REPLICATE_USE_CACHE', false),
            'cache_duration' => env('REPLICATE_CACHE_DURATION', 600), // minutes
            'api_endpoint' => env('REPLICATE_API_ENDPOINT', 'https://api.replicate.com'),
            'models' => [
                'google/imagen-3' => 'Imagen 3',
                'google/imagen-3-fast' => 'Imagen 3 Fast',
                'minimax/image-01' => 'Minimax Image 0.1',
                'stability-ai/stable-diffusion-3.5-medium' => 'Stable Diffusion 3.5 Medium',

                'black-forest-labs/flux-dev-lora' => 'Flux Dev Lora',


            ],

            'default_parameters' => [
                'aspect_ratio' => '16:9',
                'number_of_images' => 1,
                'prompt_optimizer' => true
            ],
            'supported_aspect_ratio' => [
                '16:9',
                '9:16',
                '4:3',
                '3:4',
                '1:1',
            ],
            'field_mapping' => [
                'google/imagen-3' => [
                    'prompt' => 'prompt',
                    'image' => 'image',
                    'negative_prompt' => 'negative_prompt',
                    'width' => 'width',
                    'height' => 'height',
                    'aspect_ratio' => 'aspect_ratio',
                    'number_of_images' => 'number_of_images',
                    'prompt_optimizer' => 'prompt_optimizer',
                    'guidance_scale' => 'guidance_scale'
                ],

                'minimax/image-01' => [
                    'prompt' => 'prompt',
                    'image' => 'subject_reference',
                    'negative_prompt' => 'negative_prompt',
                    'width' => 'width',
                    'height' => 'height',
                    'aspect_ratio' => 'aspect_ratio',
                    'number_of_images' => 'number_of_images',
                    'prompt_optimizer' => 'prompt_optimizer'
                ],
                'luma/photon-flash' => [
                    'prompt' => 'prompt',
                    'image' => 'image_reference_url'
                ],
            ]
        ],

        'anthropic' => [
            'enabled' => env('ANTHROPIC_ENABLED', false),
            'api_key' => env('ANTHROPIC_API_KEY'),
            'model' => env('ANTHROPIC_MODEL', 'claude-sonnet-4'),
            'max_tokens' => env('ANTHROPIC_MAX_TOKENS', null),
            'temperature' => env('ANTHROPIC_TEMPERATURE', 0.7),
            'use_cache' => env('ANTHROPIC_USE_CACHE', false),
            'cache_duration' => env('ANTHROPIC_CACHE_DURATION', 600), // minutes
            'models' => [
                'claude-sonnet-4-20250514'      => 'Claude Sonnet 4',
                'claude-3-7-sonnet-latest'    => 'Claude Sonnet 3.7',
                'claude-3-5-sonnet-latest'    => 'Claude Sonnet 3.5',
                'claude-3-opus-latest'       => 'Claude Opus 4',
                'claude-3-5-haiku-latest'     => 'Claude Haiku 3.5',
            ],
        ],
    ],


];
