<?php

namespace Modules\WhiteLabel\Providers;

use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Settings\Filament\Pages\Settings;
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

            // Bind event for applying white label settings
            event_bind('mw.front', function () {
                app(WhiteLabelService::class)->applyWhiteLabelSettings();
            });
            event_bind('mw.admin', function () {
                app(WhiteLabelService::class)->applyWhiteLabelSettings();
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

        // Register Microweber module
        Microweber::module(WhiteLabelModule::class);
    }
}
