<?php

namespace Modules\Settings\Providers;


use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\FileManager\Filament\Pages\FileManagerPageAdmin;
use Modules\Settings\Filament\Pages\{AdminAdvancedPage,
    AdminCustomTagsPage,
    AdminEmailPage,
    AdminExperimentalPage,
    AdminGeneralPage,
    AdminLanguagePage,
    AdminLoginRegisterPage,
    AdminMaintenanceModePage,
    AdminPrivacyPolicyPage,
    AdminSeoPage,
    AdminShopAutoRespondEmailPage,
    AdminShopGeneralPage,
    AdminShopOtherPage,
    AdminTemplateCustomizerPage,
    AdminTemplatePage,
    Settings};
use Modules\Settings\Filament\Resources\ModuleConfigurationResource;
use Modules\Settings\Filament\Resources\TranslationResource;

class SettingsServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Settings';

    protected string $moduleNameLower = 'settings';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        // Register Livewire components
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
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));

        // Register main settings page
        FilamentRegistry::registerPage(Settings::class);

        // Register translation resource
        FilamentRegistry::registerResource(TranslationResource::class);

        // Register website settings pages
        FilamentRegistry::registerPage(AdminAdvancedPage::class);
        FilamentRegistry::registerPage(AdminCustomTagsPage::class);
        FilamentRegistry::registerPage(AdminEmailPage::class);
        FilamentRegistry::registerPage(AdminExperimentalPage::class);
        FilamentRegistry::registerPage(AdminGeneralPage::class);
        FilamentRegistry::registerPage(AdminLanguagePage::class);
        FilamentRegistry::registerPage(AdminLoginRegisterPage::class);
        FilamentRegistry::registerPage(AdminMaintenanceModePage::class);
        FilamentRegistry::registerPage(AdminPrivacyPolicyPage::class);

        FilamentRegistry::registerPage(AdminSeoPage::class);
        FilamentRegistry::registerPage(AdminTemplatePage::class);
        FilamentRegistry::registerPage(AdminTemplateCustomizerPage::class);

        // Register shop settings pages
        FilamentRegistry::registerPage(AdminShopAutoRespondEmailPage::class);
        FilamentRegistry::registerPage(AdminShopGeneralPage::class);
        FilamentRegistry::registerPage(AdminShopOtherPage::class);

        // Register module configuration resource
        FilamentRegistry::registerResource(ModuleConfigurationResource::class);

        // Register filament page for Microweber module settings
        // FilamentRegistry::registerPage(SettingsModuleSettings::class);

        // Register Microweber module
        // Microweber::module(\Modules\Settings\Microweber\SettingsModule::class);

    }

}
