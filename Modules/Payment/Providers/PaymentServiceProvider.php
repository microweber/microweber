<?php

namespace Modules\Payment\Providers;

use Illuminate\Contracts\Container\Container;
use Livewire\Livewire;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource;
use Modules\Payment\Livewire\PaymentMethodSelector;
use Modules\Payment\Microweber\PaymentModule;
use Modules\Payment\Repositories\PaymentMethodManager;

class PaymentServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Payment';

    protected string $moduleNameLower = 'payment';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
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
        });
        // Register Livewire Components
        Livewire::component('payment-method-selector', PaymentMethodSelector::class);

        FilamentRegistry::registerResource(PaymentProviderResource::class);

        Microweber::module(PaymentModule::class);
    }
}
