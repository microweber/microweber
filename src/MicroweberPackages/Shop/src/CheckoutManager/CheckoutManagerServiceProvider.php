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

namespace MicroweberPackages\CheckoutManager;

use Illuminate\Support\ServiceProvider;

class CheckoutManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\CheckoutManager\CheckoutManager    $checkout_manager
         */
        $this->app->singleton('checkout_manager', function ($app) {
            return new CheckoutManager();
        });
    }
}