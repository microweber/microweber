<?php

namespace MicroweberPackages\CssFrameworkBootstrap\Providers;

use Illuminate\Support\ServiceProvider;

class CssFrameworkServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('packages/microweber-css-framework-bootstrap'),
        ], ['mw-css-framework', 'public']);
    }

    public function register(): void
    {
        //
    }
}