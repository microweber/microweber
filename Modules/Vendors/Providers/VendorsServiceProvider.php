<?php

namespace Modules\Vendors\Providers;

use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Vendors\Filament\Admin\Resources\VendorResource;
use Modules\Vendors\Providers\FilamentVendorPanelProvider;
use Modules\Vendors\Services\VendorManager;
use Modules\Vendors\Services\VendorOrderNotifier;

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
        $this->app->singleton(VendorOrderNotifier::class, fn () => new VendorOrderNotifier());

        // Notify vendors when their products are ordered (email + in-app, per
        // each vendor's channel opt-in).
        $this->app->register(VendorsEventServiceProvider::class);

        // The dedicated Vendor panel (/vendor-panel) — all vendor-facing
        // functionality lives here: dashboard + stats, storefront, products,
        // orders. Gated to the vendor role via User::canAccessPanel('vendor').
        $this->app->register(FilamentVendorPanelProvider::class);

        // A pointer from the profile panel to the dedicated Vendor panel, so
        // vendors can discover their dashboard from the normal profile area.
        FilamentRegistry::registerPage(
            \Modules\Vendors\Filament\Pages\VendorPanelLink::class,
            'profile',
            'profile'
        );

        // Admin: approve + manage vendor storefronts.
        FilamentRegistry::registerResource(VendorResource::class);

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

        // Opt-in "Sold by {vendor}" badge for templates:
        //   <x-vendor-badge :content-id="content_id()" />
        \Illuminate\Support\Facades\Blade::component(
            'vendor-badge',
            \Modules\Vendors\View\Components\VendorBadge::class
        );
    }
}
