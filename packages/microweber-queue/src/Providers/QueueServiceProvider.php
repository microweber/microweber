<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Providers;

use Illuminate\Routing\Router;
use MicroweberPackages\Queue\Http\Controllers\ProcessQueueController;
use MicroweberPackages\Queue\Services\ChunkedDispatcherService;
use MicroweberPackages\Queue\Services\QueueProcessor;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class QueueServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/queue');
    }

    public function packageRegistered(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/microweber-queue.php', 'microweber-queue');

        $this->app->singleton(ChunkedDispatcherService::class, static fn () => new ChunkedDispatcherService());
        $this->app->singleton(QueueProcessor::class, static fn () => new QueueProcessor());

    }

    public function packageBooted(): void
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
