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

class TaxManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\Tax    $tax_manager
         */
        $this->app->singleton('tax_manager', function ($app) {
            return new TaxManager();
        });

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');
    }

    public function register()
    {
        $this->loadRoutesFrom((__DIR__) . '/routes/api.php');
        $this->loadRoutesFrom((__DIR__) . '/routes/web.php');
    }
}
