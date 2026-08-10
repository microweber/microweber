<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | AI Tools Configuration
    |--------------------------------------------------------------------------
    |
    | Standalone configuration for the microweber-packages/ai-tools package.
    | Domain-specific CMS tools should register themselves with the
    | ToolRegistry from their own service providers.
    |
    */

    'enabled' => env('AI_TOOLS_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Built-in tool classes auto-registered on boot
    |--------------------------------------------------------------------------
    |
    | List fully-qualified class names implementing ToolInterface.
    | Application / module code may also call AiTools::register() at runtime.
    |
    */
    'tools' => [
        \MicroweberPackages\AiTools\Tools\External\AmazonScraperTool::class,
        \MicroweberPackages\AiTools\Tools\External\GoogleTrendsTool::class,
        \MicroweberPackages\AiTools\Tools\External\SupadataTool::class,
    ],

    'defaults' => [
        'max_tries' => 500,
        'timeout' => 30,
    ],

    'services' => [
        'amazon' => [
            'enabled' => env('AI_TOOLS_AMAZON_ENABLED', true),
            'timeout' => 30,
        ],
        'google_trends' => [
            'enabled' => env('AI_TOOLS_GOOGLE_TRENDS_ENABLED', true),
            'timeout' => 30,
        ],
        'supadata' => [
            'enabled' => env('AI_TOOLS_SUPADATA_ENABLED', false),
            'api_key' => env('SUPADATA_API_KEY'),
        ],
        'youtube' => [
            'enabled' => env('AI_TOOLS_YOUTUBE_ENABLED', true),
            'api_key' => env('YOUTUBE_API_KEY'),
        ],
    ],

    'permissions' => [
        'check_permissions' => true,
        'default_permissions' => ['view content'],
    ],
];
