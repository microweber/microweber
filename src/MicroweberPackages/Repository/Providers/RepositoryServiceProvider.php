<?php

namespace MicroweberPackages\Repository\Providers;

use Illuminate\Cache\CacheManager;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;

use MicroweberPackages\Application;
use MicroweberPackages\Repository\Repositories\AbstractRepository;
use MicroweberPackages\Repository\RepositoryManager;


class RepositoryServiceProvider extends ServiceProvider
{

    public function register()
    {
//        $this->app->bind(
//            ContentRepositoryInterface::class,
//            ContentRepositoryApi::class
//        );

        $this->app->register(\Torann\LaravelRepository\RepositoryServiceProvider::class);

    }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {


//         $container = new \Illuminate\Container\Container();
//
//
//    //    $container->bind('memcached.connector', 'Illuminate\Cache\MemcachedConnector');
////
////        $app->singleton('config', function () {
////            return [
////                'cache.default' => 'memcached',
////                'cache.prefix' => '',
////                'cache.stores.memcached' => [
////                    'driver'  => 'memcached',
////                    'servers' => [
////                        [
////                            'host' => '127.0.0.1', 'port' => 11211, 'weight' => 100,
////                        ]
////                    ]
////                ]
////            ];
////        });
//
//
//        $container->singleton('config', function () {
//            return [
//                'cache.default' => 'array',
//                'cache.prefix' => '',
//                'cache.stores.array' => [
//                    'driver'  => 'array',
//                ]
//            ];
//        });
//
//        $cache = (new \Illuminate\Cache\CacheManager($container));
//   //AbstractRepository::setCacheInstance($cache);



        AbstractRepository::setCacheInstance($this->app['cache']);



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
