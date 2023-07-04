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

namespace MicroweberPackages\LiveEdit\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use MicroweberPackages\LiveEdit\Events\ServingLiveEdit;
use MicroweberPackages\LiveEdit\Http\Livewire\ModuleTemplateSelectComponent;
use MicroweberPackages\LiveEdit\Http\Middleware\DispatchServingLiveEdit;
use MicroweberPackages\LiveEdit\Http\Middleware\DispatchServingModuleSettings;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LiveEditServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-live-edit');
        $package->hasViews('microweber-live-edit');
    }

    public function registerMenu()
    {
       \MicroweberPackages\LiveEdit\Facades\LiveEditManager::getMenuInstance('top_right_menu')
           ->addChild('Products', [
            'attributes' => [
                'route' => 'admin.product.index',
                'icon' => 'products'
            ]
        ]);
    }

    public function register()
    {
        parent::register();
        View::addNamespace('microweber-live-edit', __DIR__ . '/resources/views');

        \App::singleton('LiveEditManager', function () {
            return new LiveEditManager();
        });

        Livewire::component('microweber-live-edit::module-select-template', ModuleTemplateSelectComponent::class);

        Event::listen(ServingLiveEdit::class, [$this, 'registerMenu']);
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        $router = $this->app['router'];

        $router->middlewareGroup('live_edit', [
            DispatchServingLiveEdit::class,
        ]);

        $router->middlewareGroup('module_settings', [
            DispatchServingModuleSettings::class,
        ]);

        $this->loadRoutesFrom((__DIR__) . '/../routes/api.php');
        $this->loadRoutesFrom((__DIR__) . '/../routes/web.php');
        $this->loadRoutesFrom((__DIR__) . '/../routes/live_edit.php');
    }

}
