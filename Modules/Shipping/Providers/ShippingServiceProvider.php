<?php

namespace Modules\Shipping\Providers;

use MicroweberPackages\AiTools\Support\RegistersAiTools;
use Modules\Shipping\Tools\ShippingMethodLookupTool;
use Modules\Shipping\Tools\ShippingZoneSummaryTool;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\View;
use MicroweberPackages\App\Application;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource;
use Modules\Shipping\Services\ShippingMethodManager;
use Modules\Shipping\ShippingManager;

class ShippingServiceProvider extends BaseModuleServiceProvider
{
    use RegistersAiTools;

    protected string $moduleName = 'Shipping';

    protected string $moduleNameLower = 'shipping';


    /**
     * Register the service provider.
     */
    public function register(): void
    {
        parent::register();

        $this->registerAiTools([
            ShippingMethodLookupTool::class,
            ShippingZoneSummaryTool::class,
        ]);

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));


        View::addNamespace('shipping', __DIR__ . '/../resources/views');


        $this->app->singleton('shipping_method_manager', function ($app) {

            return new ShippingMethodManager($app->make(Container::class));
        });

        $this->app->resolving('shipping_method_manager', function (ShippingMethodManager $shippingMethodManager) {
            $shippingMethodManager->extend('flat_rate', function () {
                return new \Modules\Shipping\Drivers\FlatRate();
            });

            $shippingMethodManager->extend('pickup_from_address', function () {
                return new \Modules\Shipping\Drivers\PickupFromAddress();
            });
            $shippingMethodManager->extend('shipping_to_country', function () {
                return new \Modules\Shipping\Drivers\ShippingToCountry();
            });
            $shippingMethodManager->extend('weight_based', function () {
                return new \Modules\Shipping\Drivers\WeightBased();
            });
        });

        FilamentRegistry::registerResource(ShippingProviderResource::class);

        FilamentRegistry::registerGlobalSearchEntry(
            'Shipping Provider Settings', '/admin/shipping-providers',
            ['shipping', 'shipping provider', 'shipping method', 'delivery',
             'shipping options', 'shipping settings', 'shipping rate',
             'free shipping', 'flat rate', 'pickup', 'shipping zone'],
            'Shop Settings', ['Section' => 'Shop Settings'],
        );
    }

}
