<?php

namespace Modules\SiteStats\Providers;

use MicroweberPackages\AiTools\Support\RegistersAiTools;
use Modules\SiteStats\Tools\AnalyticsAudienceBreakdownTool;
use Modules\SiteStats\Tools\AnalyticsTopPagesTool;
use Modules\SiteStats\Tools\AnalyticsTrafficReferrersTool;
use Modules\SiteStats\Tools\AnalyticsTrafficSummaryTool;

use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use Modules\SiteStats\Filament\SiteStatsDashboard;
use Modules\SiteStats\Filament\SiteStatsDashboardChart;
use Modules\SiteStats\Filament\SiteStatsEchartsWidget;
use Modules\SiteStats\Filament\Pages\SiteStatsPage;


class SiteStatsServiceProvider extends BaseModuleServiceProvider
{
    use RegistersAiTools;

    protected string $moduleName = 'SiteStats';

    protected string $moduleNameLower = 'site_stats';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerAiTools([
            AnalyticsAudienceBreakdownTool::class,
            AnalyticsTopPagesTool::class,
            AnalyticsTrafficReferrersTool::class,
            AnalyticsTrafficSummaryTool::class,
        ]);

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
        parent::register();

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));


        FilamentRegistry::registerWidget(
            SiteStatsEchartsWidget::class,
            \App\Filament\Admin\Pages\Dashboard::class);

        FilamentRegistry::registerPage(SiteStatsPage::class);

        // Register Microweber module
        // Microweber::module(\Modules\SiteStats\Microweber\SiteStatsModule::class);

    }

}
