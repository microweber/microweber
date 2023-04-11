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

namespace MicroweberPackages\LiveEdit;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\LiveEdit\Http\Livewire\ModuleTemplateSelectComponent;
use MicroweberPackages\LiveEdit\Http\Middleware\DispatchServingLiveEdit;
use MicroweberPackages\LiveEdit\Http\Middleware\DispatchServingModuleSettings;

class LiveEditServiceProvider extends ServiceProvider
{
    public function register()
    {

        View::addNamespace('live-edit', __DIR__.'/resources/views');


        \App::singleton('LiveEditManager', function () {
            return new LiveEditManager();
        });


        Livewire::component('live-edit::module-select-template', ModuleTemplateSelectComponent::class);



    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $router = $this->app['router'];

        $router->middlewareGroup('live_edit', [
            DispatchServingLiveEdit::class,
        ]);

        $router->middlewareGroup('module_settings', [
            DispatchServingModuleSettings::class,
        ]);

        $this->loadRoutesFrom((__DIR__) . '/routes/api.php');
        $this->loadRoutesFrom((__DIR__) . '/routes/web.php');
        $this->loadRoutesFrom((__DIR__) . '/routes/live_edit.php');
    }

}
