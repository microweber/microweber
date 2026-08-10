<?php

namespace MicroweberPackages\Repository\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;
use MicroweberPackages\Repository\Repositories\AbstractRepository;
use MicroweberPackages\Repository\RepositoryManager;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
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

    public function boot()
    {
    }
}
