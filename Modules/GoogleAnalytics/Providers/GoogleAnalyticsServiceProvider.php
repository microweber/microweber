<?php

namespace Modules\GoogleAnalytics\Providers;

use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
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

        FilamentRegistry::registerGlobalSearchEntry(
            'Google Analytics Settings', '/admin/settings/google-analytics',
            ['google analytics', 'analytics', 'ga4', 'tracking',
             'google tag', 'measurement id', 'website analytics'],
            'Settings', ['Section' => 'Website Settings'],
        );


        $isGoogleMeasurementEnabled = get_option('google-measurement-enabled', 'website') == 1;
        if ($isGoogleMeasurementEnabled) {
            event_bind('mw.pingstats.response', function () {
                //    @todo
                //    $dispatchGoogleEventsJs = new \Modules\GoogleAnalytics\Support\DispatchGoogleEventsJs();
                //   return $dispatchGoogleEventsJs->convertEvents();
            });
        }
    }
}
