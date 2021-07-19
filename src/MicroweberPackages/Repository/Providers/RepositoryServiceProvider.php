<?php

namespace MicroweberPackages\Repository\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;

use MicroweberPackages\Application;
use MicroweberPackages\Repository\RepositoryManager;

class RepositoryServiceProvider extends ServiceProvider
{

//    public function register()
//    {
//        $this->app->bind(
//            ContentRepositoryInterface::class,
//            ContentRepository::class
//        );
//    }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        /**
         * @property  RepositoryManager repository_manager
         */

        $this->app->singleton('repository_manager', function ($app) {
            /**
             * @var Application $app
             */
            return new RepositoryManager($app->make(Container::class));
        });

     }

}
