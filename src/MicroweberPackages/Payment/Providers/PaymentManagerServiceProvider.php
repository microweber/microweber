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

namespace MicroweberPackages\Payment\Providers;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Payment\Filament\Admin\Resources\PaymentProviderResource;
use MicroweberPackages\Payment\PaymentManager;
use MicroweberPackages\Payment\PaymentMethodManager;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class PaymentManagerServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package
            ->hasViews('payment')
            ->hasAssets()
            ->name('microweber-packages/payment');
    }


    public function register(): void
    {
//        $this->publishes([
//            __DIR__ . '/../resources/assets' => public_path('vendor/microweber-packages/payment'),
//        ], 'public');

        parent::register();


        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        View::addNamespace('payment', __DIR__ . '/../resources/views');

        $this->app->singleton('payment_method_manager', function ($app) {

            return new PaymentMethodManager($app->make(Container::class));
        });

        $this->app->resolving('payment_method_manager', function (PaymentMethodManager $paymentManager) {
            $paymentManager->extend('pay_on_delivery', function () {
                return new \MicroweberPackages\Payment\Drivers\PayOnDelivery();
            });

            $paymentManager->extend('paypal', function () {
                return new \MicroweberPackages\Payment\Drivers\PayPal();
            });
            $paymentManager->extend('stripe', function () {
                return new \MicroweberPackages\Payment\Drivers\Stripe();
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

        parent::boot();
    }
}
