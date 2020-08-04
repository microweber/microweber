<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

if (class_exists(\MicroweberPackages\App\LaravelApplication::class)) {
    $app = new \MicroweberPackages\App\LaravelApplication(
        realpath(__DIR__ . '/../')
    );
} else {
    $app = new Illuminate\Foundation\Application(
        $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
    );
}

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    \Illuminate\Contracts\Http\Kernel::class,
    \MicroweberPackages\App\Http\Kernel::class
);

if (class_exists(\App\Console\Kernel::class)) {
    $app->singleton(
        \Illuminate\Contracts\Console\Kernel::class,
        \App\Console\Kernel::class
    );
} else {
    $app->singleton(
        'Illuminate\Contracts\Console\Kernel',
        'MicroweberPackages\App\Console\Kernel'
    );
}

if (class_exists(\App\Exceptions\Handler::class)) {
    $app->singleton(
        \Illuminate\Contracts\Debug\ExceptionHandler::class,
        \App\Exceptions\Handler::class
    );
} else {
    $app->singleton(
        'Illuminate\Contracts\Debug\ExceptionHandler',
        'MicroweberPackages\App\Exceptions\Handler'
    );
}

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
