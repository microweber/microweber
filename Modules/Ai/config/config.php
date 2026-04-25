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

    'enabled' => env('AI_ENABLED', false),

    'disable_settings' => env('AI_DISABLE_SETTINGS', false),

    'default_driver' => env('AI_DRIVER', 'openai'),
    'default_driver_images' => env('AI_DRIVER_IMAGES', 'replicate'),

    'mcp' => [
        'enabled' => env('AI_MCP_ENABLED', false),
        'endpoint' => env('AI_MCP_ENDPOINT', '/api/mcp'),
        'transport' => env('AI_MCP_TRANSPORT', 'http-jsonrpc'),
        'protocol_version' => env('AI_MCP_PROTOCOL_VERSION', '2025-03-26'),
        'supported_protocol_versions' => array_filter(array_map(
            static fn (string $v): string => trim($v),
            explode(',', (string) env(
                'AI_MCP_SUPPORTED_PROTOCOL_VERSIONS',
                '2024-11-05,2025-03-26,2025-06-18'
            ))
        )),
        // Default token TTL in days. Tokens issued without an
        // explicit expires_at inherit this lifetime so forever-
        // tokens are an opt-in choice (set the env var to 0 to
        // disable). Backward-compat: any token already in the DB
        // with NULL expires_at stays null — only newly-issued
        // tokens pick up the default.
        'token_default_ttl_days' => (int) env('AI_MCP_TOKEN_DEFAULT_TTL_DAYS', 90),
        // Laravel log channel that receives one `mcp.tool.call`
        // line per `tools/call` invocation. Defaults to `stack`
        // (whatever `config('logging.default')` resolves to). Point
        // it at a dedicated channel with a JSON formatter when
        // ingesting into Loki / ELK / Datadog.
        'log_channel' => (string) env('AI_MCP_LOG_CHANNEL', 'stack'),
        // Slow-tool warn threshold in milliseconds. Tool calls
        // whose wallclock duration exceeds this trigger an extra
        // `mcp.tool.slow` warning line on the same channel as the
        // regular `mcp.tool.call` info line. Set to 0 to disable
        // the slow-tool warning.
        //
        // PHP can't preemptively cancel a synchronous tool call
        // mid-execution without pcntl_alarm (which isn't safe in
        // a generic catalog), so this is observability rather
        // than enforcement -- the warning line is the signal that
        // a tool is regressing past its expected p95 latency.
        'slow_tool_warn_ms' => (int) env('AI_MCP_SLOW_TOOL_WARN_MS', 5000),
        // Audit-event sampling. Today only the high-volume
        // `token.used` event consults the sampler; rare events
        // (token.issued / revoked / denied / rotated) are always
        // recorded.
        'audit' => [
            // 1.0 = record every authenticated request (default).
            // 0.1 = record ~10% (recommended for high-throughput
            // integrations to keep mcp_client_token_events small).
            // 0.0 = never record token.used (last_used_at still
            // updates).
            'sample_used' => (float) env('AI_MCP_AUDIT_SAMPLE_USED', 1.0),
        ],
        'server_name' => env('AI_MCP_SERVER_NAME', 'Microweber AI MCP'),
        'server_version' => env('AI_MCP_SERVER_VERSION', '0.1.0'),
        'client_token_prefix' => env('AI_MCP_CLIENT_TOKEN_PREFIX', 'mcp_'),
        'auth' => [
            'guard' => env('AI_MCP_AUTH_GUARD', 'sanctum'),
            'require_admin' => (bool) env('AI_MCP_REQUIRE_ADMIN', true),
            'required_abilities' => array_filter(array_map(
                static fn (string $ability): string => trim($ability),
                explode(',', (string) env('AI_MCP_REQUIRED_ABILITIES', 'mcp:access'))
            )),
            'admin_scope' => env('AI_MCP_ADMIN_SCOPE', 'mcp:admin'),
            'admin_only_tools' => array_filter(array_map(
                static fn (string $tool): string => trim($tool),
                explode(',', (string) env('AI_MCP_ADMIN_ONLY_TOOLS', ''))
            )),
            'admin_only_modules' => array_filter(array_map(
                static fn (string $module): string => trim($module),
                explode(',', (string) env('AI_MCP_ADMIN_ONLY_MODULES', ''))
            )),
        ],
    ],

    'secret_store' => [
        'driver' => env('AI_SECRET_STORE_DRIVER', 'pass'),
        'pass' => [
            'enabled' => (bool) env('AI_SECRET_STORE_ENABLED', false),
            'binary' => env('AI_SECRET_STORE_BINARY', 'pass'),
            'path_prefix' => env('AI_SECRET_STORE_PATH_PREFIX', 'microweber'),
            'environment' => env('AI_SECRET_STORE_ENV', env('APP_ENV', 'production')),
            'store_dir' => env('AI_SECRET_STORE_DIR'),
        ],
    ],

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
            'base_url' => env('OPENAI_BASE_URL', null),
            'model' => env('OPENAI_MODEL', 'gpt-4-turbo'),
            'max_tokens' => env('OPENAI_MAX_TOKENS', null),
            'temperature' => env('OPENAI_TEMPERATURE', 0.7),
            'models' => [
                'gpt-4o-mini' => 'GPT 4o Mini',
                'gpt-4.1-nano' => 'GPT 4.1 Nano',
                'gpt-4.1-mini' => 'GPT 4.1 Mini',
                'gpt-4o' => 'GPT 4o',
                'gpt-4-turbo' => 'GPT 4 Turbo',
            ],
        ],

        'ollama' => [
            'enabled' => env('OLLAMA_ENABLED', false),
            'url' => env('OLLAMA_API_URL', 'http://localhost:11434/api'),
            'model' => env('OLLAMA_MODEL', 'llama3.1'),
            'api_key' => env('OLLAMA_API_KEY'),
        ],

        'openrouter' => [
            'enabled' => env('OPENROUTER_ENABLED', false),
            'model' => env('OPENROUTER_MODEL', 'meta-llama/llama-3.3-70b-instruct'),
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
            'model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
            'max_tokens' => env('GEMINI_MAX_TOKENS', null),
            'temperature' => env('GEMINI_TEMPERATURE', 0.7),
            'api_endpoint' => env('GEMINI_API_ENDPOINT', 'https://generativelanguage.googleapis.com/v1beta'),
            'models' => [
                'gemini-2.5-flash' => 'Gemini 2.5 Flash',
                'gemini-1.5-flash' => 'Gemini 1.5 Flash',

            ],
        ],


        'replicate' => [
            'enabled' => env('REPLICATE_ENABLED', false),
            'api_key' => env('REPLICATE_API_KEY'),
            'model' => env('REPLICATE_MODEL', 'google/imagen-4-fast'),
            'max_tokens' => env('REPLICATE_MAX_TOKENS', null),
            'temperature' => env('REPLICATE_TEMPERATURE', 0.7),
            'api_endpoint' => env('REPLICATE_API_ENDPOINT', 'https://api.replicate.com'),
            'models' => [
                'google/imagen-4-fast' => 'Imagen 4 Fast',
                'google/imagen-3-fast' => 'Imagen 3 Fast',
                'google/imagen-3' => 'Imagen 3',
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
            'cache_duration' => env('ANTHROPIC_CACHE_DURATION', 600), // minutes
            'models' => [
                'claude-sonnet-4-20250514' => 'Claude Sonnet 4',
                'claude-3-7-sonnet-latest' => 'Claude Sonnet 3.7',
                'claude-3-5-sonnet-latest' => 'Claude Sonnet 3.5',
                'claude-3-opus-latest' => 'Claude Opus 4',
                'claude-3-5-haiku-latest' => 'Claude Haiku 3.5',
            ],
        ],

        'tavily' => [
            'enabled' => env('TAVILY_ENABLED', false),
            'api_key' => env('TAVILY_API_KEY'),
            'api_endpoint' => env('TAVILY_API_ENDPOINT', 'https://api.tavily.com'),
            'search_depth' => env('TAVILY_SEARCH_DEPTH', 'basic'), // basic or advanced
            'include_answer' => env('TAVILY_INCLUDE_ANSWER', true),
            'include_raw_content' => env('TAVILY_INCLUDE_RAW_CONTENT', false),
            'max_results' => env('TAVILY_MAX_RESULTS', 5),
            'include_images' => env('TAVILY_INCLUDE_IMAGES', false),
            'include_domains' => env('TAVILY_INCLUDE_DOMAINS', []),
            'exclude_domains' => env('TAVILY_EXCLUDE_DOMAINS', []),
        ],

        'supadata' => [
            'enabled' => env('SUPADATA_ENABLED', false),
            'api_key' => env('SUPADATA_API_KEY'),
            'model' => env('SUPADATA_MODEL', 'supadata-default'),
            'api_endpoint' => env('SUPADATA_API_ENDPOINT', 'https://api.supadata.com'),
            'max_tokens' => env('SUPADATA_MAX_TOKENS', null),
            'temperature' => env('SUPADATA_TEMPERATURE', 0.7),
            'models' => [
                'supadata-default' => 'Supadata Default',
                'supadata-pro' => 'Supadata Pro',
                'supadata-turbo' => 'Supadata Turbo',
            ],
        ],

        'fal' => [
            'enabled' => env('FAL_ENABLED', false),
            'api_key' => env('FAL_API_KEY'),
            'model' => env('FAL_MODEL', 'fal-ai/nano-banana'),
            'api_endpoint' => env('FAL_API_ENDPOINT', 'https://fal.run'),
            'timeout' => env('FAL_TIMEOUT', 300),
            'models' => [
                'fal-ai/imagen3/fast' => 'Imagen 3 Fast',
                'fal-ai/nano-banana' => 'Nano Banana',
            ],
            'default_parameters' => [
                'image_size' => 'square_hd',
                'num_inference_steps' => 25,
                'guidance_scale' => 7.5,
                'num_images' => 1,
                'safety_tolerance' => 2,
            ],
            'supported_image_sizes' => [
                'square_hd' => 'Square HD (1024x1024)',
                'square' => 'Square (512x512)',
                'portrait_4_3' => 'Portrait 4:3 (768x1024)',
                'portrait_16_9' => 'Portrait 16:9 (576x1024)',
                'landscape_4_3' => 'Landscape 4:3 (1024x768)',
                'landscape_16_9' => 'Landscape 16:9 (1024x576)',
            ],
            'field_mapping' => [
                'fal-ai/nano-banana' => [
                    'prompt' => 'prompt',
                    'negative_prompt' => 'negative_prompt',
                    'image_size' => 'image_size',
                    'num_inference_steps' => 'num_inference_steps',
                    'guidance_scale' => 'guidance_scale',
                    'num_images' => 'num_images',
                    'seed' => 'seed',
                    'safety_tolerance' => 'safety_tolerance',
                ],

            ],
        ],
    ],


];
