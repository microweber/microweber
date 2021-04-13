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

namespace MicroweberPackages\Checkout;

use Illuminate\Support\Facades\View;
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
         * @property \MicroweberPackages\Checkout    $checkout_manager
         */
        $this->app->singleton('checkout_manager', function ($app) {
            return new CheckoutManager();
        });

        View::addNamespace('checkout', __DIR__.'/resources/views');

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
    }
}
