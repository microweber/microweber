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

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use Livewire\LivewireServiceProvider as BaseLivewireServiceProvider;
use LivewireUI\Modal\LivewireModalServiceProvider;
use MicroweberPackages\Livewire\Mechanisms\FrontendAssets\MwFrontendAssets;
use MicroweberPackages\Livewire\Mechanisms\HandleRequests\MwHandleRequests;

//use Rappasoft\LaravelLivewireTables\LaravelLivewireTablesServiceProvider;

class LivewireServiceProvider extends BaseLivewireServiceProvider
{
    /**
     * Whether or not to defer the loading of this service
     * provider until it's needed
     *
     * @var boolean
     */
  //  protected $defer = true;


//    public function provides()
//    {
//        return ['Livewire\Livewire'];
//    }
    protected function registerConfig()
    {
        $config = __DIR__.'/config/livewire.php';

        $this->publishes([$config => base_path('config/livewire.php')], ['livewire', 'livewire:config']);

        $this->mergeConfigFrom($config, 'livewire');
    }

    protected function mergeConfigFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);
        $this->app['config']->set($key, array_merge($config, require $path,));

    }

    protected function getMechanisms()
    {

        return [
            \Livewire\Mechanisms\PersistentMiddleware\PersistentMiddleware::class,
            \Livewire\Mechanisms\HandleComponents\HandleComponents::class,
            MwHandleRequests::class,
            MwFrontendAssets::class,
            \Livewire\Mechanisms\ExtendBlade\ExtendBlade::class,
            \Livewire\Mechanisms\CompileLivewireTags::class,
            \Livewire\Mechanisms\ComponentRegistry::class,
            \Livewire\Mechanisms\RenderComponent::class,
            \Livewire\Mechanisms\DataStore::class,
        ];
    }
    protected function registerLivewireSingleton()
    {
        $this->app->singleton(LivewireManager::class);
        $this->app->alias(LivewireManager::class, 'livewire');


        app('livewire')->setProvider($this);
       // app('livewire')->setProvider($this);
        Config::set('livewire.asset_url', url('aaaaaaaaaa'));


    }


    public function register()
    {

        include_once __DIR__ . '/Helpers/helpers.php';

        $this->mergeConfigFrom(__DIR__ . '/config/livewire.php', 'livewire');

        View::addNamespace('livewire', __DIR__ . '/resources/views');
        // Load datatables
        //   app()->register(LaravelLivewireTablesServiceProvider::class);
        //  $this->mergeConfigFrom(__DIR__.'/config/livewire-tables.php', 'livewire-tables');


        parent::register();

           // Load UI Modal
        app()->register(LivewireModalServiceProvider::class);
        $this->mergeConfigFrom(__DIR__ . '/config/livewire-ui-modal.php', 'livewire-ui-modal');

        $this->registerRoutes();



    }

    protected function registerRoutes()
    {

//        app($this::class)->setScriptRoute(function ($handle) {
//            return Route::get('/livewire/livewire.js', $handle);
//        });



        Route::get('/livewire/livewire.js', [\MicroweberPackages\Livewire\Http\Controllers\LivewireJavaScriptAssets::class, 'source']);
        Route::get('/livewire/livewire.js.map', [\MicroweberPackages\Livewire\Http\Controllers\LivewireJavaScriptAssets::class, 'maps']);

    }

}
