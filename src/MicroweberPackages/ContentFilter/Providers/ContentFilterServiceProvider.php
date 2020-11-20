<?php

namespace MicroweberPackages\ContentFilter\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ContentFilterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        View::addNamespace('content_filter', __DIR__.'/../resources/views');

        $this->loadMigrationsFrom(__DIR__ . '/../migrations/');
    }
}
