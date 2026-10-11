<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Visual Editor Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the visual drag-and-drop editor.
    |
    */

    'enabled' => env('VISUAL_EDITOR_ENABLED', true),

    'block_types' => [
        'text',
        'image',
        'video',
        'heading',
        'button',
        'spacer',
        'divider',
        'columns',
        'embed',
        'gallery',
    ],

    'default_block_settings' => [
        'text' => [
            'align' => 'left',
        ],
        'image' => [
            'align' => 'center',
            'width' => '100%',
        ],
        'heading' => [
            'level' => 'h2',
            'align' => 'left',
        ],
    ],

    'ui' => [
        'show_block_toolbar' => true,
        'show_settings_panel' => true,
        'auto_save' => true,
        'auto_save_interval' => 30, // seconds
    ],

    'permissions' => [
        'create_blocks' => ['admin', 'editor'],
        'delete_blocks' => ['admin', 'editor'],
        'reorder_blocks' => ['admin', 'editor', 'author'],
        'edit_blocks' => ['admin', 'editor', 'author'],
    ],

];
