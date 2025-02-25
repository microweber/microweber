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

     /*   // Bind event for white label settings in admin
        app('events')->listen('mw.admin.settings.website', function ($params = false) {
            if (app('ui')->powered_by_link_enabled() && app('ui')->service_links_enabled()) {
                return '<div type="modules.white_label::settings_card" class="mw-lazy-load-module" id="white_label_settings"></div>';
            }
        });

        // Bind event for applying white label settings
        app('events')->listen('mw.after.boot', function () {
            app(WhiteLabelService::class)->applyWhiteLabelSettings();
        });

        // Register API endpoints
        app('api')->register('save_white_label_config', function ($params) {
            return app(WhiteLabelService::class)->saveWhiteLabelConfig($params);
        });*/
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
