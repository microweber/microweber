<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Page Generation Settings
    |--------------------------------------------------------------------------
    |
    | These settings control how pages are generated using the AI wizard.
    |
    */

    'page_generation' => [
        'default_sections' => [
            'header',
            'content',
            'features',
            'testimonials',
            'contact',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Processing
    |--------------------------------------------------------------------------
    |
    | Settings for how content is processed and formatted.
    |
    */

    'processing' => [
        'markdown' => [
            'enabled' => true,
            'auto_process' => false,
        ],
    ],
];
