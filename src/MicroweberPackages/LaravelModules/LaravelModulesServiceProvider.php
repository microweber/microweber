<?php

namespace MicroweberPackages\LaravelModules;

use Composer\InstalledVersions;
use Illuminate\Foundation\Console\AboutCommand;
use MicroweberPackages\Core\Providers\Concerns\MergesConfig;
use MicroweberPackages\LaravelModules\Contracts\LaravelModulesCacheRepositoryContract;
use MicroweberPackages\LaravelModules\Helpers\SplClassLoader;
use MicroweberPackages\LaravelModules\Repositories\LaravelModulesCacheRepository;
use MicroweberPackages\LaravelModules\Repositories\LaravelModulesFileRepository;
use Nwidart\Modules\Contracts\ActivatorInterface;
use Nwidart\Modules\Contracts\RepositoryInterface;
use Nwidart\Modules\Exceptions\InvalidActivatorClass;
use Nwidart\Modules\Laravel\LaravelFileRepository;
use Nwidart\Modules\Providers\BootstrapServiceProvider;
use Nwidart\Modules\Providers\ConsoleServiceProvider;
use Nwidart\Modules\Providers\ContractsServiceProvider;
use Nwidart\Modules\Support\Stub;

//from https://github.com/allenwakeup/laravel-modules/

class LaravelModulesServiceProvider extends \Nwidart\Modules\LaravelModulesServiceProvider
{
    use MergesConfig;

    public function boot()
    {


        AboutCommand::add('Laravel-Modules', [
            'Version' => fn () => InstalledVersions::getPrettyVersion('nwidart/laravel-modules'),
        ]);
    }


    public function register()
    {

        // autoload_add_namespace(base_path() . '/Modules/', 'Modules\\');
        //  autoload_add_namespace(base_path() . '/Modules/Test3/app', 'Modules\\Test3');
//        spl_autoload_register(function ($class) {
//            $loader = new \MicroweberPackages\LaravelModules\Helpers\SplClassLoader();
//            if ($loader->autoloadClass($class)) {
//                return true;
//            }
//        });

        $this->mergeConfigFrom(__DIR__ . '/config/modules.php', 'modules');
       // $this->app->singleton(RepositoryInterface::class, LaravelModulesFileRepository::class);

        $this->registerServices();
        $this->setupStubPath();
        $this->registerProviders();
        //     $this->app->bind (RepositoryInterface::class, LaravelModulesDatabaseRepository::class);
    //    $this->app->singleton(RepositoryInterface::class, LaravelModulesDatabaseRepository::class);
        //  $this->app->bind (RepositoryInterface::class, LaravelModulesDatabaseCacheRepository::class);
//        $this->app->singleton(ActivatorInterface::class, function ($app) {
//
//            $activator = app()->config->get('modules.activator');
//            $class = app()->config->get('modules.activators.'.$activator)['class'];
//
//            if ($class === null) {
//                throw InvalidActivatorClass::missingConfig();
//            }
//
//            return new $class($app);
//
//        });


        $this->registerNamespaces();
        $this->registerModules();
    }

    protected function registerModules()
    {
        $this->app->register(BootstrapServiceProvider::class);


    }
    protected function registerServices()
    {
//        $this->app->singleton(Contracts\RepositoryInterface::class, function ($app) {
//            $path = app()->config->get('modules.paths.modules');
//
//            return new Laravel\LaravelFileRepository($app, $path);
//        });

        $this->app->singleton(LaravelModulesCacheRepositoryContract::class, LaravelModulesCacheRepository::class);

        $this->app->singleton(ActivatorInterface::class, function ($app) {
            $activator = app()->config->get('modules.activator');
            $class = app()->config->get('modules.activators.'.$activator)['class'];

            if ($class === null) {
                throw InvalidActivatorClass::missingConfig();
            }

            return new $class($app);
        });
        $this->app->alias(RepositoryInterface::class, 'modules');
    }
    public function setupStubPath()
    {
        $path = $this->app['config']->get('modules.stubs.path') ?? __DIR__ . '/Commands/stubs';
        Stub::setBasePath($path);

        $this->app->booted(function ($app) {
            /** @var RepositoryInterface $moduleRepository */
            $moduleRepository = $app[RepositoryInterface::class];
            if ($moduleRepository->config('stubs.enabled') === true) {
                Stub::setBasePath($moduleRepository->config('stubs.path'));
            }
        });
    }

    protected function registerProviders()
    {
        $this->app->register(ConsoleServiceProvider::class);
        $this->app->bind(RepositoryInterface::class, LaravelModulesFileRepository::class);

        //  $this->app->register(ContractsServiceProvider::class);
    }
//    protected function registerNamespaces()
//    {
//
//    }

//    protected function registerServices()
//    {
//        $this->app->singleton(Contracts\RepositoryInterface::class, function ($app) {
//            $path = app()->config->get('modules.paths.modules');
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
