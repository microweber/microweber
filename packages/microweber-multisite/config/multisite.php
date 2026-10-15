<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Multisite master switch
    |--------------------------------------------------------------------------
    | When false, the manager resolves no site and applies no isolation — the
    | app behaves exactly as a single-site install.
    */
    'enabled' => env('MULTISITE_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Config path
    |--------------------------------------------------------------------------
    | Directory scanned for "{domain}/microweber.php" site markers. Null uses
    | the framework's config_path(). Override only for unusual layouts.
    */
    'config_path' => null,

    /*
    |--------------------------------------------------------------------------
    | What to isolate per site
    |--------------------------------------------------------------------------
    | cache       — per-site file cache path + cache key prefix
    | session     — per-site session files path + cookie name
    | userfiles   — MW_USERFILES_FOLDER_NAME = "userfiles/{site}" (media/uploads)
    | public_disk — repoint Laravel's "public" disk at a per-site subfolder.
    |               Use this in a clean Laravel PoC; leave OFF under Microweber,
    |               where userfiles isolation already covers media.
    */
    'isolate' => [
        'cache' => true,
        'session' => true,
        'userfiles' => true,
        'public_disk' => false,
        // app_url — point APP_URL (+ the URL generator root) at the request host
        // for this domain, so URLs + content routing resolve against the site's
        // own domain even if .env.<domain> has a stale/placeholder APP_URL.
        'app_url' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Path layout
    |--------------------------------------------------------------------------
    | userfiles_base — relative to storage/app/public
    | cache_base     — relative to storage/
    | sessions_base  — relative to storage/
    | sites_segment  — folder that namespaces per-site cache/session trees,
    |                  e.g. storage/framework/cache/sites/{site}
    */
    'paths' => [
        'userfiles_base' => 'userfiles',
        'cache_base' => 'framework/cache',
        'sessions_base' => 'framework/sessions',
        'sites_segment' => 'sites',
    ],

];
