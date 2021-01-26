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

namespace MicroweberPackages\Shipping;

use Illuminate\Support\ServiceProvider;

class ShippingManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\Shipping\ShippingManager    $shipping_manager
         */
        $this->app->singleton('shipping_manager', function ($app) {
            return new ShippingManager();
        });

     //   $this->loadMigrationsFrom(__DIR__ . '/migrations/');
    }
}