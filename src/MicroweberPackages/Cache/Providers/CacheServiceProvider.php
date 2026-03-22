<?php

declare(strict_types=1);

namespace MicroweberPackages\Cache\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Cache\Services\PageCacheService;
use MicroweberPackages\Cache\Services\FragmentCacheService;
use MicroweberPackages\Cache\View\Blade\FragmentCacheDirective;
use MicroweberPackages\Cache\Console\Commands\CacheWarmCommand;
use MicroweberPackages\Cache\Console\Commands\CacheClearCommand;
use MicroweberPackages\Cache\Console\Commands\CacheStatsCommand;

/**
 * Cache Service Provider
 * 
 * Registers all cache services, middleware, and console commands.
 * 
 * @package MicroweberPackages\Cache\Providers
 */
class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register Page Cache Service
        $this->app->singleton(PageCacheService::class, function ($app) {
            return new PageCacheService();
        });

        // Register Fragment Cache Service
        $this->app->singleton(FragmentCacheService::class, function ($app) {
            return new FragmentCacheService();
        });

        // Merge configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../config/page-cache.php',
            'cache.page'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../config/fragment-cache.php',
            'cache.fragment'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish configuration
        $this->publishes([
            __DIR__ . '/../config/page-cache.php' => config_path('page-cache.php'),
            __DIR__ . '/../config/fragment-cache.php' => config_path('fragment-cache.php'),
        ], 'microweber-cache-config');

        // Register Blade directives
        FragmentCacheDirective::register();

        // Register console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                CacheWarmCommand::class,
                CacheClearCommand::class,
                CacheStatsCommand::class,
            ]);
        }
    }
}
