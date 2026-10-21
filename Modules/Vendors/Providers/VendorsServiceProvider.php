<?php

namespace Modules\Vendors\Providers;

use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Vendors\Filament\Admin\Resources\VendorResource;
use Modules\Vendors\Filament\Pages\MyVendorProducts;
use Modules\Vendors\Filament\Pages\MyVendorProfile;
use Modules\Vendors\Services\VendorManager;

class VendorsServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Vendors';

    protected string $moduleNameLower = 'vendors';

    public function register(): void
    {
        parent::register();

        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));

        $this->app->singleton('vendor_manager', fn () => new VendorManager());
        $this->app->alias('vendor_manager', VendorManager::class);

        // Admin: approve + manage vendor storefronts.
        FilamentRegistry::registerResource(VendorResource::class);

        // Profile panel: vendor self-service (only visible to vendors —
        // the pages gate their own navigation on the vendor role).
        FilamentRegistry::registerPage(MyVendorProfile::class, 'profile', 'profile');
        FilamentRegistry::registerPage(MyVendorProducts::class, 'profile', 'profile');

        FilamentRegistry::registerGlobalSearchEntry(
            'Vendors', '/admin/vendors',
            ['vendor', 'marketplace', 'seller', 'merchant', 'store', 'shop owner'],
            'Shop', ['Section' => 'Shop'],
        );
    }

    public function boot(): void
    {
        // Make sure the `vendor` role exists so admins can assign it from
        // Users → Roles. Cheap + idempotent.
        app(VendorManager::class)->ensureRole();
    }
}
