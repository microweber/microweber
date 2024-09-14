<?php

namespace MicroweberPackages\LaravelModules;

use MicroweberPackages\Core\Providers\Concerns\MergesConfig;
use MicroweberPackages\LaravelModules\Helpers\SplClassLoader;
use MicroweberPackages\LaravelModules\Repositories\LaravelModulesDatabaseCacheRepository;
use MicroweberPackages\LaravelModules\Repositories\LaravelModulesFileRepository;
use Nwidart\Modules\Contracts\RepositoryInterface;
use Nwidart\Modules\Support\Stub;

//from https://github.com/allenwakeup/laravel-modules/

class LaravelModulesServiceProvider extends \Nwidart\Modules\LaravelModulesServiceProvider
{
    use MergesConfig;

    public function register()
    {

       // autoload_add_namespace(base_path() . '/Modules/', 'Modules\\');
      //  autoload_add_namespace(base_path() . '/Modules/Test3/app', 'Modules\\Test3');
        spl_autoload_register(function ($class) {
            if(SplClassLoader::autoloadClass($class)){
                return true;
            }
        });
        $this->mergeConfigFrom(__DIR__ . '/config/modules.php', 'modules');

        $this->registerServices();
        $this->setupStubPath();
        $this->registerProviders();
    //     $this->app->bind (RepositoryInterface::class, LaravelModulesDatabaseRepository::class);
      $this->app->bind (RepositoryInterface::class, LaravelModulesFileRepository::class);
      //  $this->app->bind (RepositoryInterface::class, LaravelModulesDatabaseCacheRepository::class);

    }
    public function setupStubPath()
    {
        $path = $this->app['config']->get('modules.stubs.path') ?? __DIR__.'/Commands/stubs';
        Stub::setBasePath($path);

        $this->app->booted(function ($app) {
            /** @var RepositoryInterface $moduleRepository */
            $moduleRepository = $app[RepositoryInterface::class];
            if ($moduleRepository->config('stubs.enabled') === true) {
                Stub::setBasePath($moduleRepository->config('stubs.path'));
            }
        });
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
