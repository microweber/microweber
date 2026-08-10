<?php

namespace MicroweberPackages\Http;

use Illuminate\Support\ServiceProvider;

class HttpServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(HttpService::class, function ($app) {
            return new HttpService($app);
        });
    }

    public function boot(): void
    {
        //
    }
}
