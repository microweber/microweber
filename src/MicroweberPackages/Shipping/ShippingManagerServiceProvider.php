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
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\App\Application;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Shipping\Filament\Admin\Resources\ShippingProviderResource;

class ShippingManagerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        View::addNamespace('shipping', __DIR__ . '/resources/views');
        ModuleAdmin::registerPanelResource(ShippingProviderResource::class);
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

        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');

    }
}
