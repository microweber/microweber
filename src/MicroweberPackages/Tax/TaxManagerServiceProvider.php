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

namespace MicroweberPackages\Tax;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Tax\Filament\Admin\Resources\TaxResource;

class TaxManagerServiceProvider extends ServiceProvider
{


    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->loadRoutesFrom((__DIR__) . '/routes/api.php');
        $this->loadRoutesFrom((__DIR__) . '/routes/web.php');
        View::addNamespace('tax', __DIR__ . '/resources/views');


        /**
         * @property \MicroweberPackages\Tax\TaxManager $tax_manager
         */
        app()->singleton('tax_manager', function ($app) {
            return new TaxManager();
        });



        FilamentRegistry::registerResource(TaxResource::class);
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {


    }

}
