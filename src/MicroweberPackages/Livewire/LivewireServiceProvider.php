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
use LivewireUI\Modal\LivewireModalServiceProvider;
use Rappasoft\LaravelLivewireTables\LaravelLivewireTablesServiceProvider;

class LivewireServiceProvider extends BaseLivewireServiceProvider
{
    /**
     * Whether or not to defer the loading of this service
     * provider until it's needed
     *
     * @var boolean
     */
    protected $defer = false;


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

    public function boot()
    {
        parent::boot();
        \Illuminate\Support\Facades\Blade::directive('uppercase', function($expression) {
            return "<?php echo strtoupper($expression); ?>";
        });
    }
    public function register()
    {
        parent::register();


        $this->mergeConfigFrom(__DIR__.'/config/livewire.php', 'livewire');

        View::addNamespace('livewire', __DIR__ . '/resources/views');

        // Load datatables
        app()->register(LaravelLivewireTablesServiceProvider::class);
        $this->mergeConfigFrom(__DIR__.'/config/livewire-tables.php', 'livewire-tables');

        // Load UI Modal
        app()->register(LivewireModalServiceProvider::class);
        $this->mergeConfigFrom(__DIR__.'/config/livewire-ui-modal.php', 'livewire-ui-modal');


    }

    protected function registerRoutes()
    {
        parent::registerRoutes();

        RouteFacade::get('/livewire/livewire.js', [\MicroweberPackages\Livewire\Http\Controllers\LivewireJavaScriptAssets::class, 'source']);
        RouteFacade::get('/livewire/livewire.js.map', [\MicroweberPackages\Livewire\Http\Controllers\LivewireJavaScriptAssets::class, 'maps']);

    }

}
