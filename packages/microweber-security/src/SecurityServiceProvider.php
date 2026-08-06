<?php

namespace MicroweberPackages\Security;

use Illuminate\Support\ServiceProvider;

class SecurityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // One singleton per class + one canonical string alias each.
        $this->app->singleton(HtmlClean::class, function () {
            return new HtmlClean();
        });
        $this->app->alias(HtmlClean::class, 'html_clean');

        $this->app->singleton(XSSClean::class, function () {
            return new XSSClean();
        });
        $this->app->alias(XSSClean::class, 'xss_clean');

        $this->app->singleton(XSSSecurity::class, function () {
            return new XSSSecurity();
        });
        $this->app->alias(XSSSecurity::class, 'xss_security');
    }

    public function boot(): void
    {
        //
    }
}