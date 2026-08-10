<?php

namespace MicroweberPackages\ContentField;

use Illuminate\Contracts\Support\DeferrableProvider;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;
class ContentFieldServiceProvider extends MicroweberPackageServiceProvider implements DeferrableProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/content-field');
    }

    public function packageBooted(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function packageRegistered(): void
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
