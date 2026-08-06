<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Admin notifiable model
    |--------------------------------------------------------------------------
    |
    | Class used to resolve admin recipients for legacy notification saves.
    | When null, the package tries MicroweberPackages\User\Models\User then
    | App\Models\User.
    |
    */
    'admin_user_model' => env('NOTIFICATION_ADMIN_USER_MODEL'),

    /*
    |--------------------------------------------------------------------------
    | Admin query column / value
    |--------------------------------------------------------------------------
    |
    | Applied when resolving admin notifiables. Set admin_column to null to
    | skip the where clause (e.g. notify all users of the model).
    |
    */
    'admin_column' => env('NOTIFICATION_ADMIN_COLUMN', 'is_admin'),
    'admin_value' => env('NOTIFICATION_ADMIN_VALUE', 1),

    /*
    |--------------------------------------------------------------------------
    | Admin HTTP routes
    |--------------------------------------------------------------------------
    */
    'load_admin_routes' => env('NOTIFICATION_LOAD_ADMIN_ROUTES', true),

    /*
    | Route prefix for admin notification endpoints. When null and the CMS
    | helper mw_admin_prefix_url_legacy() exists, that value is used.
    | Otherwise defaults to "admin".
    */
    'admin_route_prefix' => env('NOTIFICATION_ADMIN_ROUTE_PREFIX'),

    /*
    | Middleware stack for admin notification routes.
    */
    'admin_middleware' => array_values(array_filter(explode(',', (string) env(
        'NOTIFICATION_ADMIN_MIDDLEWARE',
        'web,admin'
    )))),

    /*
    |--------------------------------------------------------------------------
    | View namespace
    |--------------------------------------------------------------------------
    */
    'view_namespace' => 'notification',

];
