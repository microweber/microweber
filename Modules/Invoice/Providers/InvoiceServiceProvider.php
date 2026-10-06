<?php

namespace Modules\Invoice\Providers;

use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
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
        parent::register();

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/admin.php'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));

        /**
         * @property InvoiceService $invoice_service
         */
        $this->app->bind('invoice_service', function () {
            return new InvoiceService();
        });

        FilamentRegistry::registerResource(InvoiceResource::class);
        // Also list Invoices in the Billing section of the Settings page.
        FilamentRegistry::registerResource(InvoiceResource::class, \Modules\Settings\Filament\Pages\Settings::class);

        FilamentRegistry::registerPage(AdminShopInvoicesPage::class);

        FilamentRegistry::registerGlobalSearchEntry(
            'Invoice Settings', '/admin/settings/invoices',
            ['invoice', 'invoices', 'invoice settings', 'billing',
             'invoice template', 'invoice number'],
            'Shop Settings', ['Section' => 'Shop Settings'],
        );
    }
}
