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

namespace MicroweberPackages\Menu\Providers;

use Filament\Events\ServingFilament;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Menu\TranslateTables\TranslateMenu;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        View::addNamespace('menu', __DIR__ . '/../resources/views');




  //      FilamentRegistry::registerPlugin(MenuFilamentPlugin::class);



        Event::listen(ServingFilament::class, function () {




        });


    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {



        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../migrations/');


    }
}
