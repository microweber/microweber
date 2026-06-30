<?php

namespace MicroweberPackages\Format;

use Illuminate\Support\ServiceProvider;

class FormatServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('format', function () {
            return new Format();
        });
    }

    public function boot(): void
    {
        //
    }
}