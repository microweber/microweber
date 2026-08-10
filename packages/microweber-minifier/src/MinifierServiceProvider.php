<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Minifier\Services\CssMinify;
use MicroweberPackages\Minifier\Services\JsMinify;
use MicroweberPackages\Minifier\Services\MinifierService;

class MinifierServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/minifier.php', 'minifier');

        $this->app->singleton(JsMinify::class, static fn () => new JsMinify());
        $this->app->singleton(CssMinify::class, static fn () => new CssMinify());

        $this->app->singleton(MinifierService::class, function ($app) {
            return new MinifierService(
                $app->make(JsMinify::class),
                $app->make(CssMinify::class),
            );
        });

    }

    public function boot(): void
    {
        // The HTTP routes exist only so the test suite can exercise
        // MinifierController end-to-end. The CMS minifies via the CssMinify /
        // JsMinify services directly (see AssetOptimizationService), so there is
        // no reason to expose these endpoints in a normal environment — register
        // them only when running the tests.
        if ($this->app->runningUnitTests() || $this->app->environment('testing')) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'minifier');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/minifier.php' => config_path('minifier.php'),
            ], 'minifier-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/minifier'),
            ], 'minifier-views');
        }
    }
}
