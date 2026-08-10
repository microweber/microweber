<?php

namespace MicroweberPackages\ContentField;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class ContentFieldServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function register(): void
    {
        $this->app->singleton(ContentFieldManager::class, function (): ContentFieldManager {
            return new ContentFieldManager();
        });
    }

    /**
     * @return list<string>
     */
    public function provides(): array
    {
        return [ContentFieldManager::class];
    }
}
