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
use MicroweberPackages\Livewire\LivewireManager;


class LivewireServiceProvider extends BaseLivewireServiceProvider
{




    protected function mergeConfigFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);
        $this->app['config']->set($key, array_merge( $config,require $path,));
    }




    protected function registerLivewireSingleton()
    {
        $this->app->alias(\MicroweberPackages\Livewire\LivewireManager::class, 'livewire');

        $this->app->singleton(\MicroweberPackages\Livewire\LivewireManager::class);

        app('livewire')->setProvider($this);
    }


    public function register()
    {

        include_once __DIR__ . '/Helpers/helpers.php';

        $this->mergeConfigFrom(__DIR__.'/config/livewire.php', 'livewire');

        View::addNamespace('livewire', __DIR__ . '/resources/views');

        // Load datatables
    //    app()->register(LaravelLivewireTablesServiceProvider::class);
    //    $this->mergeConfigFrom(__DIR__.'/config/livewire-tables.php', 'livewire-tables');

        // Load UI Modal
//        app()->register(LivewireModalServiceProvider::class);
//        $this->mergeConfigFrom(__DIR__.'/config/livewire-ui-modal.php', 'livewire-ui-modal');
//

        // the new mw dialog
        app()->register(LivewireMwModalServiceProvider::class);

        parent::register();


    }


    protected function getMechanisms()
    {
        return [
            \Livewire\Mechanisms\PersistentMiddleware\PersistentMiddleware::class,
            \Livewire\Mechanisms\HandleComponents\HandleComponents::class,
            \Livewire\Mechanisms\HandleRequests\HandleRequests::class,
            \MicroweberPackages\Livewire\Mechanisms\FrontendAssets\MwFrontendAssets::class,
            \Livewire\Mechanisms\ExtendBlade\ExtendBlade::class,
            \Livewire\Mechanisms\CompileLivewireTags::class,
            \Livewire\Mechanisms\ComponentRegistry::class,
            \Livewire\Mechanisms\RenderComponent::class,
            \Livewire\Mechanisms\DataStore::class,
        ];
    }
    protected function registerRoutes()
    {
        parent::registerRoutes();

        RouteFacade::get('/livewire/livewire.js', [\MicroweberPackages\Livewire\Http\Controllers\LivewireJavaScriptAssets::class, 'source']);
        RouteFacade::get('/livewire/livewire.js.map', [\MicroweberPackages\Livewire\Http\Controllers\LivewireJavaScriptAssets::class, 'maps']);

    }

}
