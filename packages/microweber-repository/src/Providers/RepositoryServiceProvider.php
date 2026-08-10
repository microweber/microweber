<?php

namespace MicroweberPackages\Repository\Providers;

use Illuminate\Contracts\Container\Container;
use MicroweberPackages\Repository\Repositories\AbstractRepository;
use MicroweberPackages\Repository\RepositoryManager;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class RepositoryServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/repository');
    }

    public function packageRegistered(): void
    {
        $this->app->afterResolving('cache', function () {
            AbstractRepository::setCacheInstance($this->app['cache']);
        });

        if (class_exists(\Torann\LaravelRepository\RepositoryServiceProvider::class)
            && ! $this->app->getProvider(\Torann\LaravelRepository\RepositoryServiceProvider::class)
        ) {
            $this->app->register(\Torann\LaravelRepository\RepositoryServiceProvider::class);
        }

        $this->app->singleton(RepositoryManager::class, function ($app) {
            return new RepositoryManager($app->make(Container::class));
        });
    }

    public function packageBooted()
    {
    }
}
