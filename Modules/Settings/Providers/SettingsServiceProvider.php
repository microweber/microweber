<?php

namespace Modules\Settings\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Settings\Filament\Pages\AdminAdvancedPage;
use Modules\Settings\Filament\Pages\AdminEmailPage;
use Modules\Settings\Filament\Pages\AdminFilesPage;
use Modules\Settings\Filament\Pages\AdminGeneralPage;
use Modules\Settings\Filament\Pages\AdminLanguagePage;
use Modules\Settings\Filament\Pages\AdminLoginRegisterPage;
use Modules\Settings\Filament\Pages\AdminPrivacyPolicyPage;
use Modules\Settings\Filament\Pages\AdminSeoPage;
use Modules\Settings\Filament\Pages\AdminShopAutoRespondEmailPage;
use Modules\Settings\Filament\Pages\AdminShopCouponsPage;
use Modules\Settings\Filament\Pages\AdminShopInvoicesPage;
use Modules\Settings\Filament\Pages\AdminShopOffersPage;
use Modules\Settings\Filament\Pages\AdminShopOtherPage;
use Modules\Settings\Filament\Pages\AdminShopPaymentsPage;
use Modules\Settings\Filament\Pages\AdminShopShippingPage;
use Modules\Settings\Filament\Pages\AdminShopTaxesPage;
use Modules\Settings\Filament\Pages\AdminTemplatePage;
use Modules\Settings\Filament\Pages\AdminUpdatesPage;
use Modules\Settings\Filament\Pages\AdminShopGeneralPage;
use Modules\Settings\Filament\Pages\Settings;

class SettingsServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Settings';

    protected string $moduleNameLower = 'settings';

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
        // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));

        FilamentRegistry::registerPage(Settings::class);
        FilamentRegistry::registerPage(AdminAdvancedPage::class);
        FilamentRegistry::registerPage(AdminEmailPage::class);
        FilamentRegistry::registerPage(AdminFilesPage::class);
        FilamentRegistry::registerPage(AdminGeneralPage::class);
        FilamentRegistry::registerPage(AdminLanguagePage::class);
        FilamentRegistry::registerPage(AdminLoginRegisterPage::class);
        FilamentRegistry::registerPage(AdminPrivacyPolicyPage::class);
        FilamentRegistry::registerPage(AdminSeoPage::class);
        FilamentRegistry::registerPage(AdminShopAutoRespondEmailPage::class);
        FilamentRegistry::registerPage(AdminShopCouponsPage::class);
        FilamentRegistry::registerPage(AdminShopGeneralPage::class);
        FilamentRegistry::registerPage(AdminShopInvoicesPage::class);
        FilamentRegistry::registerPage(AdminShopOffersPage::class);
        FilamentRegistry::registerPage(AdminShopOtherPage::class);
        FilamentRegistry::registerPage(AdminShopPaymentsPage::class);
        FilamentRegistry::registerPage(AdminShopShippingPage::class);
        FilamentRegistry::registerPage(AdminShopTaxesPage::class);
        FilamentRegistry::registerPage(AdminTemplatePage::class);
        FilamentRegistry::registerPage(AdminUpdatesPage::class);


        // Register filament page for Microweber module settings
        // FilamentRegistry::registerPage(SettingsModuleSettings::class);

        // Register Microweber module
        // Microweber::module(\Modules\Settings\Microweber\SettingsModule::class);

    }

}
