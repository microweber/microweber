<?php

namespace MicroweberPackages\Format;

use Illuminate\Support\ServiceProvider;

class FormatServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(FormatService::class, function () {
            return new FormatService();
        });
    }

    public function boot(): void
    {
        //
    }
}
