<?php

namespace MicroweberPackages\LaravelModulesLivewire;

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
}
