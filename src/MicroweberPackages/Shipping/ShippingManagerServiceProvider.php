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

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\App\Application;

class ShippingManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        /**
         * @property ShippingManager $shipping_manager
         */

        $this->app->singleton('shipping_manager', function ($app) {
            /**
             * @var Application $app
             */
            return new ShippingManager($app->make(Container::class));
        });

        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');

    }
}
