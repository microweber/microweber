<?php

namespace Modules\Product\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Content\Filament\ContentModuleSettings;
use Modules\Product\Filament\Admin\Resources\ProductResource;
use Modules\Product\Filament\ProductModuleSettings;
use Modules\Product\Filament\ProductsModuleSettings;
use Modules\Product\Microweber\ProductModule;
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
        FilamentRegistry::registerResource(ProductResource::class);
        FilamentRegistry::registerPage(ProductsModuleSettings::class);

        Validator::extendImplicit('price', PriceValidator::class.'@validate', 'Invalid price value!');

        // Register filament page for Microweber module settings
       // FilamentRegistry::registerPage(ProductModuleSettings::class);

        // Register Microweber module
       Microweber::module(\Modules\Product\Microweber\ProductsModule::class);

    }

}
