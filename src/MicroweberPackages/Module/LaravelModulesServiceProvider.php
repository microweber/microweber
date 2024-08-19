<?php

namespace MicroweberPackages\Module;

use MicroweberPackages\Core\Providers\Concerns\MergesConfig;
use MicroweberPackages\Module\Activators\DatabaseActivator;
use MicroweberPackages\Module\Repositories\LaravelModulesDatabaseRepository;
use Nwidart\Modules\Exceptions\InvalidActivatorClass;
use Composer\InstalledVersions;
use Illuminate\Foundation\Console\AboutCommand;
use Nwidart\Modules\Contracts\RepositoryInterface;
use Nwidart\Modules\Contracts;
use Nwidart\Modules\Laravel;
use Nwidart\Modules\Support\Stub;

//from https://github.com/allenwakeup/laravel-modules/
class LaravelModulesServiceProvider extends \Nwidart\Modules\LaravelModulesServiceProvider
{
    use MergesConfig;

    public function register()
    {

        autoload_add_namespace(base_path() . '/Modules/', 'Modules\\');
        autoload_add_namespace(base_path() . '/Modules/Test3/app', 'Modules\\Test3');


        $this->registerServices();
        $this->setupStubPath();
        $this->registerProviders();
        $this->mergeConfigFrom(__DIR__ . '/config/modules.php', 'modules');
        $this->app->bind (RepositoryInterface::class, LaravelModulesDatabaseRepository::class);

    }

    protected function registerServices()
    {
        $this->app->singleton(Contracts\RepositoryInterface::class, function ($app) {
            $path = $app['config']->get('modules.paths.modules');

            return new LaravelModulesDatabaseRepository($app, $path);
        });
        $this->app->singleton(Contracts\ActivatorInterface::class, function ($app) {

            $class = DatabaseActivator::class;
            return new $class($app);

        });
        $this->app->alias(Contracts\RepositoryInterface::class, 'modules');
    }
}
