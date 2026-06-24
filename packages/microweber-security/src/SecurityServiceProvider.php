<?php

namespace MicroweberPackages\Security;

use Illuminate\Support\ServiceProvider;

class SecurityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('mw-html-clean', function () {
            return new HtmlClean();
        });

        $this->app->singleton('mw-xss-clean', function () {
            return new XSSClean();
        });
    }

    public function boot(): void
    {
        //
    }
}