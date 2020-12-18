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

namespace MicroweberPackages\Shop;

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
         * @property \MicroweberPackages\Shop    $shop_manager
         */
        $this->app->singleton('shop_manager', function ($app) {
            return new ShopManager();
        });

        $this->loadMigrationsFrom(__DIR__ . '/migrations/');
    }
}