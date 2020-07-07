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

namespace MicroweberPackages\Shop\CartManager;

use Illuminate\Support\ServiceProvider;

class CartManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\Shop\CartManager    $cart_manager
         */
        $this->app->singleton('cart_manager', function ($app) {
            return new CartManager();
        });
    }
}