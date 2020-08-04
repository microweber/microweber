<?php

namespace MicroweberPackages\Product\Providers;

use MicroweberPackages\Product\RecentlyViewed;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(RecentlyViewed::class, function ($app) {
            return new RecentlyViewed(
                $app['session'],
                $app['events'],
                'recently_viewed',
                session()->getId() . '_recently_viewed'
            );
        });

        $this->app->alias(RecentlyViewed::class, 'recently_viewed');
    }
}
