<?php

namespace Modules\SiteStats\Providers;

use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use Modules\SiteStats\Filament\SiteStatsDashboard;
use Modules\SiteStats\Filament\SiteStatsDashboardChart;


class SiteStatsServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'SiteStats';

    protected string $moduleNameLower = 'site_stats';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {


        event_bind('mw.pageview', function ($params = false) {
            if (get_option('stats_disabled', 'site_stats') == 1) {
                return;
            }
            if (is_admin()) {
                return;
            }
            template_foot(function () {
                $ping_js = asset('modules/site_stats/js/ping.js');
                $src = '<script id="mw-ping-stats" async defer type="text/javascript" src="' . $ping_js . '"></script>';
                return $src;
            });
        });


        // if google or fb pixel is enabled
        $this->app->register(UtmTrackingEventsServiceProvider::class);
        $this->app->register(SiteStatsEventsLocalTrackingServiceProvider::class);


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
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));


        Livewire::component('microweber-module-sitestats::dashboard', SiteStatsDashboard::class);
        Livewire::component('microweber-module-sitestats::dashboard-chart', SiteStatsDashboardChart::class);


        FilamentRegistry::registerWidget(
            SiteStatsDashboardChart::class,
            \App\Filament\Admin\Pages\Dashboard::class);

        FilamentRegistry::registerWidget(
            SiteStatsDashboard::class,
            \App\Filament\Admin\Pages\Dashboard::class);


        // Register filament page for Microweber module settings
        // FilamentRegistry::registerPage(SiteStatsModuleSettings::class);

        // Register Microweber module
        // Microweber::module(\Modules\SiteStats\Microweber\SiteStatsModule::class);

    }

}
