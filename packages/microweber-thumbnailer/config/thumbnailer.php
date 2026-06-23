<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Thumbnails Storage Path
    |--------------------------------------------------------------------------
    |
    | The directory where generated thumbnails will be stored.
    | Defaults to storage_path('app/public/thumbnails').
    |
    */
    'thumbnails_path' => env('THUMBNAILER_PATH', storage_path('app/public/thumbnails')),

    /*
    |--------------------------------------------------------------------------
    | Thumbnails URL
    |--------------------------------------------------------------------------
    |
    | The base URL for serving thumbnails.
    | Defaults to /storage/thumbnails.
    |
    */
    'thumbnails_url' => env('THUMBNAILER_URL', '/storage/thumbnails'),
];