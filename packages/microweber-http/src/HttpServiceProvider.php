<?php

namespace MicroweberPackages\Http;

use Illuminate\Support\ServiceProvider;

class HttpServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('http', function ($app) {
            return new Http($app);
        });
    }

    public function boot(): void
    {
        //
    }
}