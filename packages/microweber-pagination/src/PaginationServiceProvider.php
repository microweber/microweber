<?php

declare(strict_types=1);

namespace MicroweberPackages\Pagination;

use Illuminate\Pagination\PaginationServiceProvider as LaravelPaginationServiceProvider;

/**
 * Unified Pagination Service Provider.
 *
 * Extends Laravel's own PaginationServiceProvider so the framework's
 * built-in pagination views keep working, while also registering the
 * new mw-pagination view namespace and config.
 */
class PaginationServiceProvider extends LaravelPaginationServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->mergeConfigFrom(__DIR__ . '/../config/pagination.php', 'mw-pagination');
    }

    public function boot(): void
    {
        // Register new package views under 'mw-pagination' namespace
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'mw-pagination');

        // Also register under 'pagination' for backward compat with old code
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'pagination');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/views' => $this->app->resourcePath('views/vendor/mw-pagination'),
            ], 'mw-pagination-views');

            $this->publishes([
                __DIR__ . '/../config/pagination.php' => config_path('mw-pagination.php'),
            ], 'mw-pagination-config');
        }

        parent::boot();
    }
}