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

namespace MicroweberPackages\Shipping\Providers;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\App\Application;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Shipping\Filament\Admin\Resources\ShippingProviderResource;
use MicroweberPackages\Shipping\ShippingManager;
use MicroweberPackages\Shipping\ShippingMethodManager;

class ShippingManagerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        View::addNamespace('shipping', __DIR__ . '/../resources/views');


        $this->app->singleton('shipping_method_manager', function ($app) {

            return new ShippingMethodManager($app->make(Container::class));
        });

        $this->app->resolving('shipping_method_manager', function (ShippingMethodManager $shippingMethodManager) {
            $shippingMethodManager->extend('flat_rate', function () {
                return new \MicroweberPackages\Shipping\Drivers\FlatRate();
            });

            $shippingMethodManager->extend('pickup_from_address', function () {
                return new \MicroweberPackages\Shipping\Drivers\PickupFromAddress();
            });
        });

        FilamentRegistry::registerResource(ShippingProviderResource::class);

    }

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


        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

    }
}
