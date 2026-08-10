<?php

namespace Modules\Product\Providers;

use MicroweberPackages\AiTools\Support\RegistersAiTools;
use Modules\Product\Tools\CreateProductTool;
use Modules\Product\Tools\ProductEditTool;
use Modules\Product\Tools\ProductListTool;
use Modules\Product\Tools\ProductSearchTool;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
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
    use RegistersAiTools;

    protected string $moduleName = 'Product';

    protected string $moduleNameLower = 'product';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerAiTools([
            CreateProductTool::class,
            ProductEditTool::class,
            ProductListTool::class,
            ProductSearchTool::class,
        ]);

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
        parent::register();

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

        // AI-105 / TICKET-AY (cycle-118 2026-05-09): Module Contracts
        // DI binding. Callers can type-hint
        // `Modules\Product\Contracts\InventoryServiceContract` and
        // receive the singleton InventoryService. The brief asked for
        // ProductContract under src/MicroweberPackages/Products/ —
        // mapping the brief's intent to the actual layout: Product
        // is a Module (no \MicroweberPackages\Products\ namespace),
        // and the canonical "product" public surface other modules
        // depend on is InventoryService (stock checks, reservations,
        // deductions in cart/checkout flows).
        $this->app->singleton(
            \Modules\Product\Contracts\InventoryServiceContract::class,
            fn ($app) => $app->make(InventoryService::class)
        );

    }

}
