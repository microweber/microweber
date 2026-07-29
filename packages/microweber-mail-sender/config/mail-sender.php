<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Master switch
    |--------------------------------------------------------------------------
    */
    'enabled' => env('MW_MAIL_SENDER_ENABLED', env('MAIL_SENDER_ENABLED', true)),

    /*
    |--------------------------------------------------------------------------
    | Transport
    |--------------------------------------------------------------------------
    |
    | Supported: "smtp", "php", "gmail", "cpanel", "plesk", "config", "log", "array"
    | - "config" leaves Laravel's mail.php values untouched
    | - "php" uses PHP's native mail() via the sendmail/mail transport
    | - "gmail" / "cpanel" / "plesk" apply known host/port defaults on top of SMTP
    |
    */
    'transport' => env('MW_MAIL_SENDER_TRANSPORT', env('MAIL_MAILER', 'smtp')),

    /*
    |--------------------------------------------------------------------------
    | From address
    |--------------------------------------------------------------------------
    */
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', null),
        'name' => env('MAIL_FROM_NAME', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | SMTP credentials
    |--------------------------------------------------------------------------
    */
    'smtp' => [
        'host' => env('MAIL_HOST', '127.0.0.1'),
        'port' => (int) env('MAIL_PORT', 587),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
        'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Hostname (optional subject prefix)
    |--------------------------------------------------------------------------
    |
    | When add_hostname_to_subject is true, the subject becomes "[hostname] …".
    | Falls back to parse_url(config('app.url'), PHP_URL_HOST) when empty.
    |
    */
    'hostname' => env('MAIL_SENDER_HOSTNAME', null),

    /*
    |--------------------------------------------------------------------------
    | Blade view used for the simple HTML email body
    |--------------------------------------------------------------------------
    */
    'view' => env('MAIL_SENDER_VIEW', 'mail-sender::emails.simple'),
];
