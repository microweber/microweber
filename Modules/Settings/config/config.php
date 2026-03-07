<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin URL
    |--------------------------------------------------------------------------
    |
    | This value specifies the admin URL prefix. The default is 'admin'
    | which means the admin panel is accessible at /admin.
    |
    */
    'admin_url' => env('MW_ADMIN_URL', 'admin'),

    /*
    |--------------------------------------------------------------------------
    | Admin URL Legacy
    |--------------------------------------------------------------------------
    |
    | This value specifies the legacy admin URL prefix for backwards
    | compatibility with older Microweber versions.
    |
    */
    'admin_url_legacy' => env('MW_ADMIN_URL_LEGACY', 'admin/legacy'),

    /*
    |--------------------------------------------------------------------------
    | Admin Allowed IPs
    |--------------------------------------------------------------------------
    |
    | This value restricts admin access to specific IP addresses.
    | Comma-separated list of IP addresses or CIDR ranges.
    | Leave empty to allow all IPs.
    |
    */
    'admin_allowed_ips' => env('MW_ADMIN_ALLOWED_IPS', ''),

];
