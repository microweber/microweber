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

        // CMS and package consumers resolve `html_clean` via app()->html_clean
        $this->app->singleton('html_clean', function ($app) {
            return $app->make(HtmlClean::class);
        });

        // Legacy alias used by early extractions
        $this->app->singleton('mw-html-clean', function ($app) {
            return $app->make(HtmlClean::class);
        });

        $this->app->singleton(XSSClean::class, function () {
            return new XSSClean();
        });

        $this->app->singleton('mw-xss-clean', function ($app) {
            return $app->make(XSSClean::class);
        });

        $this->app->singleton(XSSSecurity::class, function () {
            return new XSSSecurity();
        });

        $this->app->bind('xss_security', function ($app) {
            return $app->make(XSSSecurity::class);
        });
    }

    public function boot(): void
    {
        //
    }
}