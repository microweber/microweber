<?php

namespace Modules\Invoice\Providers;

use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Invoice\Filament\Resources\InvoiceResource;

class InvoiceServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Invoice';

    protected string $moduleNameLower = 'invoice';

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


        FilamentRegistry::registerResource(InvoiceResource::class);
    }
}
