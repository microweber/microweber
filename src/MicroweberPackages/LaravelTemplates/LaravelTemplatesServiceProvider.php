<?php

namespace MicroweberPackages\LaravelTemplates;

use Composer\InstalledVersions;
use Illuminate\Foundation\Console\AboutCommand;
use MicroweberPackages\LaravelModules\Repositories\LaravelModulesFileRepository;
use MicroweberPackages\LaravelTemplates\Contracts\TemplateActivatorInterface;
use MicroweberPackages\LaravelTemplates\Contracts\TemplatesRepositoryInterface;
use MicroweberPackages\LaravelTemplates\Providers\TemplatesBootstrapServiceProvider;
use MicroweberPackages\LaravelTemplates\Providers\TemplatesConsoleServiceProvider;
use MicroweberPackages\LaravelTemplates\Providers\TemplatesContractsServiceProvider;
use MicroweberPackages\LaravelTemplates\Repositories\LaravelTemplatesFileRepository;
use Nwidart\Modules\Contracts\RepositoryInterface;
use Nwidart\Modules\Exceptions\InvalidActivatorClass;

//from https://github.com/allenwakeup/laravel-modules/

class LaravelTemplatesServiceProvider extends \Nwidart\Modules\LaravelModulesServiceProvider
{
    // use MergesConfig;
    public function boot()
    {
      //  $this->registerNamespaces();
        $this->registerModules();

        AboutCommand::add('Laravel-Templates', [
            'Version' => fn () => InstalledVersions::getPrettyVersion('nwidart/laravel-modules'),
        ]);
    }
    public function register()
    {


        $this->mergeConfigFrom(__DIR__ . '/config/templates.php', 'templates');
        $this->app->singleton(TemplatesRepositoryInterface::class, function ($app) {
            $path = $app['config']->get('templates.paths.modules');
            dd($path);
            return new LaravelTemplatesFileRepository($app, $path);
        });
        $this->app->singleton(TemplateActivatorInterface::class, function ($app) {

            $activator = $app['config']->get('templates.activator');
            $class = $app['config']->get('templates.activators.' . $activator)['class'];

            if ($class === null) {
                throw InvalidActivatorClass::missingConfig();
            }

            return new $class($app);
        });
        $this->registerServices();
        //  $this->setupStubPath();
        $this->registerProviders();
        $this->app->bind(TemplatesRepositoryInterface::class, LaravelTemplatesFileRepository::class);

    }

    protected function registerServices()
    {



        $this->app->alias(TemplatesRepositoryInterface::class, 'templates');
    }

    protected function registerProviders()
    {
        $this->app->register(TemplatesConsoleServiceProvider::class);
        $this->app->register(TemplatesContractsServiceProvider::class);
    }
    protected function registerModules()
    {
        $this->app->register(TemplatesBootstrapServiceProvider::class);
    }

}
