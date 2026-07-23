<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pixum Cache Directory
    |--------------------------------------------------------------------------
    |
    | The directory where generated placeholder images will be stored.
    |
    */
    'cache_path' => env('MEDIA_PIXUM_CACHE_PATH', storage_path('app/public/pixum')),

    /*
    |--------------------------------------------------------------------------
    | Default Placeholder Dimensions
    |--------------------------------------------------------------------------
    |
    | The default width and height for placeholder images when not specified.
    |
    */
    'default_width' => 200,
    'default_height' => 200,

    /*
    |--------------------------------------------------------------------------
    | Placeholder Background Colour (RGBA)
    |--------------------------------------------------------------------------
    |
    | The background colour for generated placeholder images.
    |
    */
    'background_color' => [
        'r' => 239,
        'g' => 236,
        'b' => 236,
        'a' => 0,
    ],

    /*
    |--------------------------------------------------------------------------
    | Max allowed dimensions
    |--------------------------------------------------------------------------
    |
    | Safety cap to prevent abuse (e.g. requests for 99999x99999 images).
    |
    */
    'max_width' => 4000,
    'max_height' => 4000,
];