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

namespace MicroweberPackages\Order\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Module\Facades\ModuleManager;
use MicroweberPackages\Order\Http\Controllers\OrdersController;
use MicroweberPackages\Order\OrderManager;

class OrderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\Order    $order_manager
         */
        $this->app->singleton('order_manager', function ($app) {
            return new OrderManager();
        });

        View::addNamespace('order', dirname(__DIR__) . '/resources/views');

        $this->loadRoutesFrom(dirname(__DIR__) . '/routes/web.php');
        $this->loadRoutesFrom(dirname(__DIR__) . '/routes/api.php');


    }
}