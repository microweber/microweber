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
    // Per-site on multisite: default under the (per-site) userfiles tree so each
    // domain keeps its own thumbnails instead of a shared storage/app/public/
    // thumbnails. THUMBNAILER_PATH env overrides. Falls back to the plain
    // storage path when used standalone (no userfiles helper).
    'thumbnails_path' => env('THUMBNAILER_PATH')
        ?: (function_exists('userfiles_path')
            ? rtrim(userfiles_path(), '/\\') . DIRECTORY_SEPARATOR . 'thumbnails'
            : storage_path('app/public/thumbnails')),

    /*
    |--------------------------------------------------------------------------
    | Thumbnails URL
    |--------------------------------------------------------------------------
    |
    | The base URL for serving thumbnails.
    | Defaults to /storage/thumbnails.
    |
    */
    'thumbnails_url' => env('THUMBNAILER_URL')
        ?: (function_exists('userfiles_url')
            ? rtrim(userfiles_url(), '/') . '/thumbnails'
            : '/storage/thumbnails'),
];