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

namespace MicroweberPackages\Shop\OrderManager;

use Illuminate\Support\ServiceProvider;

class OrderManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\Shop\OrderManager    $order_manager
         */
        $this->app->singleton('order_manager', function ($app) {
            return new OrderManager();
        });
    }
}