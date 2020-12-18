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

$app = new \MicroweberPackages\App\LaravelApplication(
    realpath(__DIR__ . '/../')
);

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

if (class_exists(\App\Http\Kernel::class,false)) {
    $app->singleton(
        \Illuminate\Contracts\Http\Kernel::class,
        \App\Http\Kernel::class
    );
} else {
    $app->singleton(
        \Illuminate\Contracts\Http\Kernel::class,
        \MicroweberPackages\App\Http\Kernel::class
    );
}


if (class_exists(\App\Console\Kernel::class,false)) {
    $app->singleton(
        \Illuminate\Contracts\Console\Kernel::class,
        \App\Console\Kernel::class
    );
} else {
    $app->singleton(
        \Illuminate\Contracts\Console\Kernel::class,
        \MicroweberPackages\App\Console\Kernel::class
    );

}

$app->singleton(
    \Illuminate\Contracts\Debug\ExceptionHandler::class,
    \MicroweberPackages\App\Exceptions\Handler::class
);

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