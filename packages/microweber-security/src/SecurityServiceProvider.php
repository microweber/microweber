<?php

namespace MicroweberPackages\Security;

use Illuminate\Support\ServiceProvider;

class SecurityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(HtmlClean::class, function () {
            return new HtmlClean();
        });

        $this->app->singleton(XSSClean::class, function () {
            return new XSSClean();
        });

        $this->app->singleton(XSSSecurity::class, function () {
            return new XSSSecurity();
        });
    }

    public function boot(): void
    {
        //
    }
}
