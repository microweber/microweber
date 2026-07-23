<?php

namespace Modules\WhiteLabel\Providers;

use Filament\Events\ServingFilament;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\AdminLiveEditSidebarElementStyleEditorPage;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\AdminLiveEditSidebarTemplateSettingsPage;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\EditorTools\AddContentModalPage;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\EditorTools\CodeEditorModuleSettingsPage;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\EditorTools\ModulePresetsModuleSettingsPage;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\EditorTools\ResetContentModuleSettingsPage;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\UnlockPackage\UnlockPackageModuleSettingsPage;
use MicroweberPackages\Microweber\Facades\Microweber;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Modules\Settings\Filament\Pages\Settings;
use Modules\WhiteLabel\Filament\Admin\WhiteLabelLicenseManager;
use Modules\WhiteLabel\Filament\Pages\WhiteLabelSettingsAdminSettingsPage;
use Modules\WhiteLabel\Microweber\WhiteLabelModule;
use Modules\WhiteLabel\Services\WhiteLabelService;

class WhiteLabelServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'WhiteLabel';

    protected string $moduleNameLower = 'white_label';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {

      if (mw_is_installed()) {

            // Register Livewire components
            Livewire::component('white-label-license-manager', WhiteLabelLicenseManager::class);

            // Bind event for applying white label settings
            event_bind('mw.front', function () {
                app(WhiteLabelService::class)->applyWhiteLabelSettings();
            });
            event_bind('mw.admin', function () {

                app(WhiteLabelService::class)->applyWhiteLabelSettings();

            });


            Event::listen(ServingFilament::class, function () {
                app(WhiteLabelService::class)->applyWhiteLabelSettings();
                $panelId = \Filament\Facades\Filament::getCurrentPanel()->getId();
                if ($panelId == 'admin') {
                    ModuleAdmin::registerLiveEditSettingsUrl('white_label/admin', WhiteLabelSettingsAdminSettingsPage::getUrl());
                }
            });


      }
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

        // Register WhiteLabelService
        $this->app->singleton(WhiteLabelService::class, function ($app) {
            return new WhiteLabelService();
        });

        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(WhiteLabelSettingsAdminSettingsPage::class);

        FilamentRegistry::registerGlobalSearchEntry(
            'White Label / Branding Settings', '/admin/settings/white-label',
            ['white label', 'branding', 'brand name', 'powered by',
             'rebrand', 'custom branding', 'logo branding'],
            'Settings', ['Section' => 'Customization Settings'],
        );

        // Register Microweber module
        Microweber::module(WhiteLabelModule::class);







    }
}
