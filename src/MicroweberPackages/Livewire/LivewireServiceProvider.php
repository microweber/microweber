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

use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use Livewire\LivewireServiceProvider as BaseLivewireServiceProvider;
use Livewire\Mechanisms\HandleRequests\HandleRequests;
use LivewireUI\Modal\LivewireModalServiceProvider;
use MicroweberPackages\Livewire\Mechanisms\FrontendAssets\MwLivewireFrontendAssets;
use MicroweberPackages\Livewire\Mechanisms\HandleRequests\MwLivewireHandleRequests;
use Rappasoft\LaravelLivewireTables\LaravelLivewireTablesServiceProvider;

class LivewireServiceProvider extends BaseLivewireServiceProvider
{
    /**
     * Whether or not to defer the loading of this service
     * provider until it's needed
     *
     * @var boolean
     */
 #   protected $defer = false;



    protected function mergeConfigFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);
        $this->app['config']->set($key, array_merge( $config,require $path,));
    }


//    protected function registerLivewireSingleton()
//    {
//        $this->app->singleton(LivewireManager::class);
//        $this->app->alias(LivewireManager::class, 'livewire');
//     }

    protected function getMechanisms()
    {
        return [
            \Livewire\Mechanisms\PersistentMiddleware\PersistentMiddleware::class,
            \Livewire\Mechanisms\HandleComponents\HandleComponents::class,
            \Livewire\Mechanisms\HandleRequests\HandleRequests::class,
            \Livewire\Mechanisms\FrontendAssets\FrontendAssets::class,
          //  MwLivewireFrontendAssets::class,
            \Livewire\Mechanisms\ExtendBlade\ExtendBlade::class,
            \Livewire\Mechanisms\CompileLivewireTags\CompileLivewireTags::class,
             \Livewire\Mechanisms\ComponentRegistry::class,
         // MwLivewireComponentRegistry::class,
            \Livewire\Mechanisms\RenderComponent::class,
            \Livewire\Mechanisms\DataStore::class,
        ];
    }
    protected function registerConfig()
    {
        $config = __DIR__.'/config/livewire.php';

        $this->publishes([$config => base_path('config/livewire.php')], ['livewire', 'livewire:config']);

        $this->mergeConfigFrom($config, 'livewire');
    }
    protected function registerMechanisms()
    {
        foreach ($this->getMechanisms() as $mechanism) {
            app($mechanism)->register();
        }
    }

    public function register()
    {
        app()->bind(\Livewire\Mechanisms\FrontendAssets\FrontendAssets::class, MwLivewireFrontendAssets::class);
        app()->bind(\Livewire\Mechanisms\HandleRequests\HandleRequests::class, MwLivewireHandleRequests::class);


        $this->registerLivewireSingleton();
        $this->registerConfig();
        $this->bootEventBus();
        $this->registerMechanisms();
        $this->registerRoutes();
        include_once __DIR__ . '/Helpers/helpers.php';

        $this->mergeConfigFrom(__DIR__.'/config/livewire.php', 'livewire');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        View::addNamespace('livewire', __DIR__ . '/resources/views');



        // Load datatables
      //  app()->register(LaravelLivewireTablesServiceProvider::class);
        $this->mergeConfigFrom(__DIR__.'/config/livewire-tables.php', 'livewire-tables');

        // Load UI Modal
//        app()->register(LivewireModalServiceProvider::class);
//        $this->mergeConfigFrom(__DIR__.'/config/livewire-ui-modal.php', 'livewire-ui-modal');
//

        // the new mw dialog
        app()->register(LivewireMwModalServiceProvider::class);
       // $resolver = app()->make(MwLivewireComponentResolver::class);
       //  dd($reg,$resolver);
        //app()->make(\Livewire\Mechanisms\ComponentRegistry::class)->resolveMissingComponent($resolver);

        parent::register();


    }

    protected function registerRoutes()
    {


//        RouteFacade::get('/livewire/livewire.js', [\MicroweberPackages\Livewire\Http\Controllers\LivewireJavaScriptAssets::class, 'source']);
//        RouteFacade::get('/livewire/livewire.js.map', [\MicroweberPackages\Livewire\Http\Controllers\LivewireJavaScriptAssets::class, 'maps']);

    }

}
