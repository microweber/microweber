<?php

if(defined('LARAVEL_START')){
    return;
}

ini_set('memory_limit', '1024M');


if (version_compare(phpversion(), "7.3.0", "<=")) {
    exit("Error: You must have PHP version 7.3 or greater to run Microweber");
}


if (!function_exists('openssl_random_pseudo_bytes')) {
    exit('Error: OpenSSL PHP extension is required to run Microweber');
}

if (!function_exists('json_encode')) {
    exit('Error: JSON PHP extension is required to run Microweber');
}

if (!function_exists('gd_info')) {
    exit('Error: GD PHP extension is required to run Microweber');
}

if (!class_exists('PDO') ) {
    exit('Error: PDO PHP extension is required to run Microweber');
}

if (!class_exists('Locale') ) {
    exit('Error: Intl PHP extension is required to run Microweber');
}

if (!class_exists('XMLReader') ) {
    exit('Error: XML PHP extension is required to run Microweber');
}


if (function_exists("date_default_timezone_set")) {
    @date_default_timezone_set(@date_default_timezone_get());
}

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylorotwell@gmail.com>
 */

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so that we do not have to worry about the
| loading of any our classes "manually". Feels great to relax.
|
*/

require __DIR__ . '/bootstrap/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let's turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight these users.
|
*/

$app = require_once __DIR__ . '/bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can simply call the run method,
| which will execute the request and send the response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have whipped up for them.
|
*/

$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
