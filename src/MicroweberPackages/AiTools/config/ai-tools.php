<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Tools Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file controls the AI Tools package settings.
    |
    */

    /**
     * Enable/disable the AI Tools system.
     */
    'enabled' => env('AI_TOOLS_ENABLED', true),

    /**
     * Tool Classes
     *
     * Register your tool classes here. They will be automatically
     * registered with the ToolRegistry on boot.
     */
    'tools' => [
        // Content tools
        // \MicroweberPackages\AiTools\Tools\Content\CreateContentTool::class,
        // \MicroweberPackages\AiTools\Tools\Content\EditContentTool::class,
        // \MicroweberPackages\AiTools\Tools\Content\GetContentTool::class,
        // \MicroweberPackages\AiTools\Tools\Content\ListContentTool::class,
        // \MicroweberPackages\AiTools\Tools\Content\SearchContentTool::class,

        // Commerce tools
        // \MicroweberPackages\AiTools\Tools\Commerce\CreateProductTool::class,
        // \MicroweberPackages\AiTools\Tools\Commerce\EditProductTool::class,
        // \MicroweberPackages\AiTools\Tools\Commerce\SearchProductTool::class,
        // \MicroweberPackages\AiTools\Tools\Commerce\SearchOrderTool::class,
        // \MicroweberPackages\AiTools\Tools\Commerce\LookupCustomerTool::class,

    // External tools
    \MicroweberPackages\AiTools\Tools\External\AmazonScraperTool::class,
    \MicroweberPackages\AiTools\Tools\External\GoogleTrendsTool::class,
    \MicroweberPackages\AiTools\Tools\External\SupadataTool::class,
    // \MicroweberPackages\AiTools\Tools\External\YouTubeTranscriptionTool::class,
    // \MicroweberPackages\AiTools\Tools\External\GenerateImageTool::class,
    ],

    /**
     * Default tool settings
     */
    'defaults' => [
        'max_tries' => 500,
        'timeout' => 30,
    ],

    /**
     * External service configuration
     */
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

    /**
     * Permission settings
     */
    'permissions' => [
        'check_permissions' => true,
        'default_permissions' => ['view content'],
    ],
];
