<?php

namespace MicroweberPackages\LaravelModules;

use MicroweberPackages\LaravelModules\Repositories\LaravelModulesFileRepository;
use Nwidart\Modules\Contracts\RepositoryInterface;

//from https://github.com/allenwakeup/laravel-modules/

class LaravelModulesServiceProvider extends \Nwidart\Modules\LaravelModulesServiceProvider
{
   // use MergesConfig;

    public function register()
    {

       // autoload_add_namespace(base_path() . '/Modules/', 'Modules\\');
      //  autoload_add_namespace(base_path() . '/Modules/Test3/app', 'Modules\\Test3');
        $this->mergeConfigFrom(__DIR__ . '/config/modules.php', 'modules');


        $this->registerServices();
        $this->setupStubPath();
        $this->registerProviders();
    //     $this->app->bind (RepositoryInterface::class, LaravelModulesDatabaseRepository::class);
         $this->app->bind (RepositoryInterface::class, LaravelModulesFileRepository::class);

    }
//    protected function registerNamespaces()
//    {
//
//    }

//    protected function registerServices()
//    {
//        $this->app->singleton(Contracts\RepositoryInterface::class, function ($app) {
//            $path = $app['config']->get('modules.paths.modules');
//
//            return new LaravelModulesDatabaseRepository($app, $path);
//        });
//        $this->app->singleton(Contracts\ActivatorInterface::class, function ($app) {
//
//            $class = DatabaseActivator::class;
//            return new $class($app);
//
//        });
//        $this->app->alias(Contracts\RepositoryInterface::class, 'modules');
//    }
}
