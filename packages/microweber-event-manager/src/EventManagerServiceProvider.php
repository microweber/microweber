<?php

namespace MicroweberPackages\Event;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class EventManagerServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        // Unbind all listeners when the application is terminating so that
        // closures (and anything they captured) can be garbage-collected.
        // This is critical for long-running processes and test suites.
        $this->app->terminating(function (): void {
            if ($this->app->resolved(EventService::class)) {
                /** @var EventService $manager */
                $manager = $this->app->make(EventService::class);
                $manager->unbindAll();
            }
        });
    }

    /**
     * Register the application services.
     *
     * Uses the factory pattern: a fresh {@see LaravelEvent} adapter is injected
     * into every {@see EventService} instance so no static state leaks between
     * container cycles (e.g. PHPUnit tests).
     */
    public function register(): void
    {
        $this->app->singleton(EventService::class, function (): EventService {
            return new EventService(new LaravelEvent());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return list<string>
     */
    public function provides(): array
    {
        return [EventService::class];
    }
}
