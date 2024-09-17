<?php

namespace MicroweberPackages\LaravelModulesLivewire;

use Illuminate\Support\Str;
use Mhmiton\LaravelModulesLivewire\Support\Decomposer;
use MicroweberPackages\LaravelModules\Repositories\LaravelModulesFileRepository;

//from https://github.com/mhmiton/laravel-modules-livewire

class LaravelModulesLivewireServiceProvider extends \Mhmiton\LaravelModulesLivewire\LaravelModulesLivewireServiceProvider
{
    // use MergesConfig;
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->mergeConfigFrom(
            __DIR__ . '/config/modules-livewire.php',
            'modules-livewire'
        );
    }

    protected function registerModuleComponents()
    {
        if (Decomposer::checkDependencies()->type == 'error') {
            return false;
        }
        return false;
        $modules = \Nwidart\Modules\Facades\Module::toCollection();

        $modulesLivewireNamespace = config('modules-livewire.namespace', 'Livewire');

        $modules->each(function ($module) use ($modulesLivewireNamespace) {
            $directory = (string) Str::of($module->getAppPath())
                ->append('/'.$modulesLivewireNamespace)
                ->replace(['\\'], '/');

            $moduleNamespace = method_exists($module, 'getNamespace')
                ? $module->getNamespace()
                : config('modules.namespace', 'Modules');

            $namespace = $moduleNamespace.'\\'.$module->getName().'\\'.$modulesLivewireNamespace;

            $this->registerComponentDirectory($directory, $namespace, $module->getLowerName().'::');
        });
    }
}
