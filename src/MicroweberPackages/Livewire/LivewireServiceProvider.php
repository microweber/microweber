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

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Livewire\Mechanisms\HandleRequests\MwLivewireHandleRequests;

//use Livewire\LivewireServiceProvider as BaseLivewireServiceProvider;
//use Livewire\Mechanisms\HandleRequests\HandleRequests;
//use LivewireUI\Modal\LivewireModalServiceProvider;
//use MicroweberPackages\Livewire\Mechanisms\FrontendAssets\MwLivewireFrontendAssets;
//use MicroweberPackages\Livewire\Mechanisms\HandleRequests\MwLivewireHandleRequests;
//use Rappasoft\LaravelLivewireTables\LaravelLivewireTablesServiceProvider;

class LivewireServiceProvider extends ServiceProvider
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
        $this->app['config']->set($key, array_merge($config, require $path,));
    }


//    protected function registerLivewireSingleton()
//    {
//        $this->app->singleton(LivewireManager::class);
//        $this->app->alias(LivewireManager::class, 'livewire');
//     }

//    protected function getMechanisms()
//    {
//        return [
//            \Livewire\Mechanisms\PersistentMiddleware\PersistentMiddleware::class,
//            \Livewire\Mechanisms\HandleComponents\HandleComponents::class,
//            \Livewire\Mechanisms\HandleRequests\HandleRequests::class,
//            \Livewire\Mechanisms\FrontendAssets\FrontendAssets::class,
//          //  MwLivewireFrontendAssets::class,
//            \Livewire\Mechanisms\ExtendBlade\ExtendBlade::class,
//            \Livewire\Mechanisms\CompileLivewireTags\CompileLivewireTags::class,
//             \Livewire\Mechanisms\ComponentRegistry::class,
//         // MwLivewireComponentRegistry::class,
//            \Livewire\Mechanisms\RenderComponent::class,
//            \Livewire\Mechanisms\DataStore::class,
//        ];
//    }
//    protected function registerConfig()
//    {
//        $config = __DIR__.'/config/livewire.php';
//
//        $this->publishes([$config => base_path('config/livewire.php')], ['livewire', 'livewire:config']);
//
//        $this->mergeConfigFrom($config, 'livewire');
//    }
//    protected function registerMechanisms()
//    {
//        foreach ($this->getMechanisms() as $mechanism) {
//            app($mechanism)->register();
//        }
//    }
    public function boot()
    {
        $livewireUrl = site_url('public/vendor/livewire/livewire.min.js');

        Livewire::setScriptRoute(function ($handle) use ($livewireUrl) {

            return Route::get($livewireUrl, $handle);
            //   return site_url().'userfiles/cache/livewire/'.\MicroweberPackages\App\LaravelApplication::APP_VERSION.'/livewire/livewire.min.js';
        });




//         Livewire::setScriptRoute(function ($handle) {
//
//             $url = asset('vendor/livewire/livewire.min.js');
//             dd($url);
//             return Route::get($url, $handle);
//             //   return site_url().'userfiles/cache/livewire/'.\MicroweberPackages\App\LaravelApplication::APP_VERSION.'/livewire/livewire.min.js';
//        });

         // dd(Route::post('livewire/update'));
//        Livewire::setUpdateRoute(function ($handle) {
//            $route = Route::post( ('/livewire/update'), $handle)->name('livewire.update');
//
//         //   $route->setUri('livewire/update');
//        //    $route->domain(site_url());
//
//           return $route;
//        });
//        Livewire::setUpdateRoute(function ($handle) {
//
//            return Route::post('livewire/update', $handle);
//        });

    }

    public function ensureLivewiereFilesAreCopiedToPublicPath()
    {
        //check if assets are copied
//        $check = public_path('vendor/livewire/livewire.min.js');
//        if(is_file($check)){
//            return;
//        }
//        Artisan::call('vendor:publish', [
//            '--provider' => 'Livewire\LivewireServiceProvider',
//            '--tag' => 'livewire:assets',
//        ]);

    }

    public function register()
    {
        $config = __DIR__.'/config/livewire.php';
        $this->mergeConfigFrom($config, 'livewire');


//

        $this->ensureLivewiereFilesAreCopiedToPublicPath();
        app()->extend(\Livewire\Mechanisms\HandleRequests\HandleRequests::class, function () {
            return new MwLivewireHandleRequests();
        });

//
//        $this->registerLivewireSingleton();
//        $this->registerConfig();
//        $this->bootEventBus();
//        $this->registerMechanisms();
//        $this->registerRoutes();
//        include_once __DIR__ . '/Helpers/helpers.php';
//
//        $this->mergeConfigFrom(__DIR__.'/config/livewire.php', 'livewire');
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        View::addNamespace('livewire', __DIR__ . '/resources/views');


        // Load UI Modal
  //       app()->register(LivewireModalServiceProvider::class);
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
