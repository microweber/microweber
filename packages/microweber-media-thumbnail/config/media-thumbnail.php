<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Thumbnail Storage Path
    |--------------------------------------------------------------------------
    |
    | The directory where generated thumbnails will be stored.
    | Defaults to storage_path('app/public/thumbnails').
    |
    */
    'thumbnails_path' => env('MEDIA_THUMBNAIL_PATH', storage_path('app/public/thumbnails')),

    /*
    |--------------------------------------------------------------------------
    | Thumbnail URL Prefix
    |--------------------------------------------------------------------------
    |
    | The base URL for serving thumbnails.
    | Defaults to /storage/thumbnails.
    |
    */
    'thumbnails_url' => env('MEDIA_THUMBNAIL_URL', '/storage/thumbnails'),

    /*
    |--------------------------------------------------------------------------
    | Table Name
    |--------------------------------------------------------------------------
    |
    | The database table used to cache thumbnail metadata.
    |
    */
    'table' => 'media_thumbnails',
];