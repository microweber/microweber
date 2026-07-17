<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Callback URL Builder
    |--------------------------------------------------------------------------
    |
    | A callable that receives the provider name and returns the full
    | OAuth callback URL. When null the package uses:
    |   url('api/social_login_process?provider=' . $provider)
    |
    */
    'callback_url_builder' => null,

    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    |
    | Each key is a provider name (facebook, google, github, twitter,
    | linkedin, microweber). Set 'enabled' to true and supply the
    | client_id / client_secret obtained from the provider console.
    |
    */
    'providers' => [

        'facebook' => [
            'enabled'       => false,
            'client_id'     => '',
            'client_secret' => '',
        ],

        'google' => [
            'enabled'       => false,
            'client_id'     => '',
            'client_secret' => '',
        ],

        'github' => [
            'enabled'       => false,
            'client_id'     => '',
            'client_secret' => '',
        ],

        'twitter' => [
            'enabled'       => false,
            'client_id'     => '',
            'client_secret' => '',
        ],

        'linkedin' => [
            'enabled'       => false,
            'client_id'     => '',
            'client_secret' => '',
        ],

        'microweber' => [
            'enabled'       => false,
            'client_id'     => '',
            'client_secret' => '',
            'server_url'    => 'https://mwlogin.com',
        ],
    ],
];