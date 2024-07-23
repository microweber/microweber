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

namespace MicroweberPackages\Payment;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Payment\Filament\Admin\Resources\PaymentProviderResource;

class PaymentManagerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        View::addNamespace('payment', __DIR__ . '/resources/views');

        $this->app->singleton('payment_method_manager', function ($app) {

            return new PaymentMethodManager($app->make(Container::class));
        });

        $this->app->resolving('payment_method_manager', function (PaymentMethodManager $paymentManager) {
             $paymentManager->extend('pay_on_delivery', function () {
                return new \MicroweberPackages\Payment\Providers\PayOnDelivery();
            });
        });




        ModuleAdmin::registerPanelResource(PaymentProviderResource::class);


    }


    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        /**
         * @property PaymentManager $payment_manager
         */

        $this->app->singleton('payment_manager', function ($app) {
            return new PaymentManager($app->make(Container::class));
        });





    }
}
