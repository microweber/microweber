<?php

namespace Modules\Invoice\Providers;

use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Invoice\Filament\Pages\AdminShopInvoicesPage;
use Modules\Invoice\Filament\Resources\InvoiceResource;
use Modules\Invoice\Services\InvoiceService;

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
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/admin.php'));

        /**
         * @property InvoiceService $invoice_service
         */
        $this->app->bind('invoice_service', function () {
            return new InvoiceService();
        });

        FilamentRegistry::registerResource(InvoiceResource::class);


        FilamentRegistry::registerPage(AdminShopInvoicesPage::class);

    }
}
