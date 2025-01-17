<?php

namespace Modules\GoogleAnalytics\Providers;

use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\GoogleAnalytics\Filament\GoogleAnalyticsModuleSettings;
use Modules\GoogleAnalytics\Filament\Pages\AdminGoogleAnalyticsSettingsPage;

class GoogleAnalyticsServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'GoogleAnalytics';
    protected string $moduleNameLower = 'google_analytics';


    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));

        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(AdminGoogleAnalyticsSettingsPage::class);


        $isGoogleMeasurementEnabled = get_option('google-measurement-enabled', 'website') == "y";
        if ($isGoogleMeasurementEnabled) {
            event_bind('mw.pingstats.response', function () {
                //    @todo
                //    $dispatchGoogleEventsJs = new \Modules\GoogleAnalytics\Support\DispatchGoogleEventsJs();
                //   return $dispatchGoogleEventsJs->convertEvents();
            });
        }
    }
}
