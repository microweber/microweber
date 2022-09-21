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
use MicroweberPackages\Application;

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
         * @property \MicroweberPackages\Shipping\ShippingManager $shipping_manager
         */

        $this->app->singleton('shipping_manager', function ($app) {
            /**
             * @var \MicroweberPackages\App\Application $app
             */
            return new ShippingManager($app->make(Container::class));
        });


        //add drivers

      /*  $this->app->resolving(\MicroweberPackages\Shipping\ShippingManager::class, function (\MicroweberPackages\Shipping\ShippingManager $shippingManager) {

            $shippingManager->extend('pickup', function () {
                return new \MicroweberPackages\Shipping\Providers\PickupDriver();
            });

        });*/

        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');




        //   $this->loadMigrationsFrom(__DIR__ . '/migrations/');
    }
}
