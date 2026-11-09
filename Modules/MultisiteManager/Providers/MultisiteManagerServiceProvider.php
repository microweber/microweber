<?php

declare(strict_types=1);

namespace Modules\MultisiteManager\Providers;

use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\MultisiteManager\Filament\Admin\Pages\AdminTenantSitesPage;
use Modules\MultisiteManager\Services\TenantSiteProvisioner;
use Modules\MultisiteManager\Support\TenantManagement;

/**
 * Wires the tenant/subsite management module.
 *
 * The provisioner service is always bound (cheap, inert on its own), but the
 * Filament admin page is only registered — and therefore only reachable — when
 * the operator has turned tenant management on via the
 * MW_MULTISITE_ENABLE_TENNANT_MANAGMENT env flag. Off by default.
 */
class MultisiteManagerServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'MultisiteManager';

    protected string $moduleNameLower = 'multisite_manager';

    public function register(): void
    {
        parent::register();

        $this->registerViews();

        $this->app->singleton(TenantSiteProvisioner::class, function ($app) {
            return new TenantSiteProvisioner(
                $app->make(\MicroweberPackages\Multisite\MultisiteManager::class),
                $app->make(\MicroweberPackages\EnvWriter\EnvWriterService::class),
            );
        });

        // The admin surface only exists when tenant management is enabled via env
        // AND we are the main site — the page is never even registered inside a
        // subsite, so subsite users cannot reach or manage tenants.
        if (TenantManagement::availableHere()) {
            FilamentRegistry::registerPage(AdminTenantSitesPage::class);

            FilamentRegistry::registerGlobalSearchEntry(
                'Subsites', '/admin/multisite-manager',
                ['multisite', 'tenant', 'subsite', 'domain', 'sites', 'tenancy'],
                'Settings', ['Section' => 'Settings'],
            );
        }
    }
}
