<?php

return [
    /*
    |--------------------------------------------------------------------------
    | CDN Sync Enabled
    |--------------------------------------------------------------------------
    |
    | Master switch to enable or disable CDN syncing globally.
    |
    */
    'enabled' => env('CDN_SYNC_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | S3-Compatible Storage Configuration
    |--------------------------------------------------------------------------
    |
    | The Laravel filesystem disk to use for CDN uploads. This should be
    | configured as an S3-compatible disk in config/filesystems.php.
    |
    */
    'disk' => env('CDN_SYNC_DISK', 'cdn'),

    /*
    |--------------------------------------------------------------------------
    | S3 Connection Settings (used when disk is not pre-configured)
    |--------------------------------------------------------------------------
    */
    'key' => env('CDN_SYNC_KEY', ''),
    'secret' => env('CDN_SYNC_SECRET', ''),
    'region' => env('CDN_SYNC_REGION', 'us-east-1'),
    'bucket' => env('CDN_SYNC_BUCKET', ''),
    'endpoint' => env('CDN_SYNC_ENDPOINT', ''),
    'url' => env('CDN_SYNC_URL', ''),
    'use_path_style_endpoint' => env('CDN_SYNC_USE_PATH_STYLE', false),

    /*
    |--------------------------------------------------------------------------
    | CDN Base URL
    |--------------------------------------------------------------------------
    |
    | If you use CloudFront or another CDN in front of S3, set the base URL
    | here. Otherwise, the S3 bucket URL will be used.
    |
    */
    'cdn_url' => env('CDN_SYNC_CDN_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | Upload Path Prefix
    |--------------------------------------------------------------------------
    |
    | All synced files will be stored under this prefix in the bucket.
    |
    */
    'path_prefix' => env('CDN_SYNC_PATH_PREFIX', 'cdn-sync'),

    /*
    |--------------------------------------------------------------------------
    | Delete Local After Sync
    |--------------------------------------------------------------------------
    |
    | Whether to delete the local file after a successful CDN upload.
    |
    */
    'delete_local' => env('CDN_SYNC_DELETE_LOCAL', false),

    /*
    |--------------------------------------------------------------------------
    | ACL for Uploaded Files
    |--------------------------------------------------------------------------
    */
    'acl' => env('CDN_SYNC_ACL', 'public-read'),
];