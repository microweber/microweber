<?php

namespace Modules\Product\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Content\Filament\ContentModuleSettings;
use Modules\Order\Events\OrderWasPaid;
use Modules\Product\Listeners\UpdateInventoryOnOrderPaid;
use Modules\Product\Filament\Admin\Resources\ProductInventoryResource;
use Modules\Product\Filament\Admin\Resources\ProductResource;
use Modules\Product\Filament\Admin\Resources\ProductVariantAttributeResource;
use Modules\Product\Filament\ProductModuleSettings;
use Modules\Product\Filament\ProductsModuleSettings;
use Modules\Product\Microweber\ProductModule;
use Modules\Product\Services\InventoryService;
use Modules\Product\Filament\Admin\ProductVariantManager;
use Modules\Product\Validators\PriceValidator;

class ProductServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Product';

    protected string $moduleNameLower = 'product';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        // Register event listeners
        Event::listen(OrderWasPaid::class, UpdateInventoryOnOrderPaid::class);

        // Register Livewire components
        \Livewire\Livewire::component('admin-product-variant-manager', ProductVariantManager::class);
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
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/admin.php'));


        Validator::extendImplicit('price', PriceValidator::class . '@validate', 'Invalid price value!');

        FilamentRegistry::registerResource(ProductResource::class);
        FilamentRegistry::registerResource(ProductVariantAttributeResource::class);
        FilamentRegistry::registerResource(ProductInventoryResource::class);
        FilamentRegistry::registerPage(ProductsModuleSettings::class);
        Microweber::module(\Modules\Product\Microweber\ProductsModule::class);

        // Register Inventory Service
        $this->app->singleton(InventoryService::class, function ($app) {
            return new InventoryService();
        });

    }

}
