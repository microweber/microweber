<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Livewire;

use Illuminate\Support\Facades\Route as RouteFacade;

use Livewire\LivewireServiceProvider as BaseLivewireServiceProvider;

class LivewireServiceProvider extends BaseLivewireServiceProvider
{
    /**
     * Whether or not to defer the loading of this service
     * provider until it's needed
     *
     * @var boolean
     */
    protected $defer = true;


    public function provides() {
        return ['Livewire\Livewire'];
    }

    protected function mergeConfigFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);
        $this->app['config']->set($key, array_merge( $config,require $path,));
    }


    protected function registerLivewireSingleton()
    {
        $this->app->singleton(LivewireManager::class);

        $this->app->alias(LivewireManager::class, 'livewire');
    }


    public function register()
    {

        parent::register();
        $this->mergeConfigFrom(__DIR__.'/config/livewire.php', 'livewire');

    }

    public function boot()
    {


        parent::boot();





    }

//    protected function registerConfig()
//    {
//        $this->mergeConfigFrom(__DIR__.'/config/livewire.php', 'livewire');
//    }

    protected function registerRoutes()
    {
        parent::registerRoutes();

        RouteFacade::get('/livewire/livewire.js', [\MicroweberPackages\Livewire\Http\Controllers\LivewireJavaScriptAssets::class, 'source']);
        RouteFacade::get('/livewire/livewire.js.map', [\MicroweberPackages\Livewire\Http\Controllers\LivewireJavaScriptAssets::class, 'maps']);
    }

}
