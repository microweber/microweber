<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Queue\Http\Controllers\ProcessQueueController;
use MicroweberPackages\Queue\Services\ChunkedDispatcher;
use MicroweberPackages\Queue\Services\QueueProcessor;

class QueueServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/microweber-queue.php', 'microweber-queue');

        $this->app->singleton(ChunkedDispatcher::class, static fn () => new ChunkedDispatcher());
        $this->app->singleton(QueueProcessor::class, static fn () => new QueueProcessor());

        $this->app->alias(ChunkedDispatcher::class, 'microweber-queue.chunked-dispatcher');
        $this->app->alias(QueueProcessor::class, 'microweber-queue.processor');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/microweber-queue.php' => config_path('microweber-queue.php'),
            ], 'microweber-queue-config');

            $this->publishes([
                __DIR__ . '/../../database/migrations' => database_path('migrations'),
            ], 'microweber-queue-migrations');
        }
    }

    protected function registerRoutes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        /** @var Router $router */
        $router = $this->app->make('router');
        $router
            ->middleware(['web'])
            ->get('microweber-queue/process', [ProcessQueueController::class, 'handle'])
            ->name('microweber-queue.process');
    }
}
