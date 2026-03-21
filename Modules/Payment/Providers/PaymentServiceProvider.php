<?php

namespace Modules\Payment\Providers;

use Illuminate\Contracts\Container\Container;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource;
use Modules\Payment\Filament\Admin\Resources\PaymentResource;
use Modules\Payment\Services\PaymentMethodManager;

class PaymentServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Payment';

    protected string $moduleNameLower = 'payment';

    /**
     * Boot the application events.
     */
public function boot(): void
{
// Navigation items are registered via FilamentRegistry in register() method.
// Do not use Filament::serving() as it's deprecated in v5.

// Load webhook routes
$this->loadRoutesFrom(module_path($this->moduleName, 'routes/webhooks.php'));
}

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));

        /* @property PaymentMethodManager $payment_method_manager */
        $this->app->singleton('payment_method_manager', function ($app) {
            return new PaymentMethodManager($app->make(Container::class));
        });

        $this->app->resolving('payment_method_manager', function (PaymentMethodManager $paymentManager) {
            $paymentManager->extend('pay_on_delivery', function () {
                return new \Modules\Payment\Drivers\PayOnDelivery();
            });

            $paymentManager->extend('paypal', function () {
                return new \Modules\Payment\Drivers\PayPal();
            });

            $paymentManager->extend('stripe', function () {
                return new \Modules\Payment\Drivers\Stripe();
            });

            $paymentManager->extend('mollie', function () {
                return new \Modules\Payment\Drivers\Mollie();
            });

            $paymentManager->extend('momomtn', function () {
                return new \Modules\Payment\Drivers\MomoMtn();
            });
        });


        FilamentRegistry::registerResource(PaymentProviderResource::class);
        FilamentRegistry::registerResource(PaymentResource::class);

        // Microweber::module(PaymentModule::class);
    }
}
