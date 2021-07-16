<?php

namespace MicroweberPackages\Repository\Providers;

use Illuminate\Support\ServiceProvider;

use MicroweberPackages\Repository\Repositories\ContentRepository;
use MicroweberPackages\Repository\Repositories\Interfaces\ContentRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(
            ContentRepositoryInterface::class,
            ContentRepository::class
        );
    }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // ContentData::observe(CreatedByObserver::class);
    }

}
