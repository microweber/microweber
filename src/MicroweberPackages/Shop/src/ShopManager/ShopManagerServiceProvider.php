<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber LTD
 *
 * For full license information see
 * http://Microweber.com/license/
 *
 */

namespace MicroweberPackages\Shop\ShopManager;

use Illuminate\Support\ServiceProvider;

class ShopManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\Shop\ShopManager    $shop_manager
         */
        $this->app->singleton('shop_manager', function ($app) {
            return new ShopManager();
        });

        $this->loadMigrationsFrom(dirname(dirname(__DIR__)) . '/migrations/');
    }
}