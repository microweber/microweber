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
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Tax\Filament\Admin\Resources\TaxResource;

class TaxManagerServiceProvider extends ServiceProvider
{


    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        View::addNamespace('tax', __DIR__ . '/resources/views');
        ModuleAdmin::registerPanelResource(TaxResource::class);
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\Tax $tax_manager
         */
        $this->app->singleton('tax_manager', function ($app) {
            return new TaxManager();
        });

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');

        $this->loadRoutesFrom((__DIR__) . '/routes/api.php');
        $this->loadRoutesFrom((__DIR__) . '/routes/web.php');
    }

}
