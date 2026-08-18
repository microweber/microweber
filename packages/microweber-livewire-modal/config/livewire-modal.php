<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default skin
    |--------------------------------------------------------------------------
    |
    | Blade view name under the package skins namespace
    | (resources/views/skins/{skin}.blade.php).
    |
    */
    'skin' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Include package CSS / JS
    |--------------------------------------------------------------------------
    |
    | When true, the modal Livewire component injects its CSS and JS into
    | the page. Set to false if you bundle them yourself.
    |
    */
    'include_css' => true,
    'include_js' => true,

    /*
    |--------------------------------------------------------------------------
    | Base z-index for the first open modal
    |--------------------------------------------------------------------------
    |
    | Each nested modal receives base_z_index + (stack_depth * z_index_step).
    |
    */
    'base_z_index' => 1100,
    'z_index_step' => 10,

    /*
    |--------------------------------------------------------------------------
    | Component defaults (all enabled by default)
    |--------------------------------------------------------------------------
    */
    'component_defaults' => [
        'modal_max_width' => '2xl',

        // Show the dimmed backdrop behind the modal
        'show_backdrop' => true,

        // Close when clicking the backdrop / outside the modal content
        'close_on_click_away' => true,

        // Close when pressing Escape
        'close_on_escape' => true,

        // Escape closes the entire stack (true) or only the top modal (false)
        'close_on_escape_is_forceful' => false,

        // Show the default skin close (X) button
        'show_close_button' => true,

        // Dispatch a modalClosed browser/Livewire event when a modal closes
        'dispatch_close_event' => false,

        // Destroy the Livewire child component when it is closed
        'destroy_on_close' => true,

        // mw.dialog wrapper defaults (used by the mw-dialog / bare skins)
        'auto_height' => true,
        'autosize' => true,
        'auto_scroll' => true,
        'draggable' => true,
    ],
];
