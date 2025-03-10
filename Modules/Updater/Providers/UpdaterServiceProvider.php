<?php

namespace Modules\Updater\Providers;

use Illuminate\Support\Facades\Blade;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use Modules\Updater\Filament\Pages\UpdaterPage;

class UpdaterServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Updater';
    protected string $moduleNameLower = 'updater';

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->registerRoutes();
        // Register filament page
        FilamentRegistry::registerPage(UpdaterPage::class);
    }

    /**
     * Boot the application events.
     */
    public function boot(): void
    {


        // Register event listeners
        $this->app['events']->listen('mw.admin', function ($params = false) {
            if ($this->isStandaloneUpdaterEnabled()) {
                // Show new update on dashboard
                $lastUpdateCheckTime = get_option('last_update_check_time', 'standalone-updater');
                if (!$lastUpdateCheckTime) {
                    $lastUpdateCheckTime = \Carbon\Carbon::now();
                }

                $showDashboardNotice = \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($lastUpdateCheckTime));
                if ($showDashboardNotice) {
                    $newVersionNumber = $this->getLatestVersion();

                    if (\Composer\Semver\Comparator::equalTo($newVersionNumber, MW_VERSION)) {
                        save_option('last_update_check_time', \Carbon\Carbon::parse('+24 hours'), 'standalone-updater');
                        return;
                    }

                    $mustUpdate = false;
                    if (\Composer\Semver\Comparator::greaterThan($newVersionNumber, MW_VERSION)) {
                        $mustUpdate = true;
                    }

                    if ($mustUpdate) {
                        $this->app['events']->listen('mw.admin.dashboard.start', function ($item) use ($newVersionNumber) {
                            // show notification
                        });
                    } else {
                        save_option('last_update_check_time', \Carbon\Carbon::parse('+24 hours'), 'standalone-updater');
                        return;
                    }
                }
            }
        });
    }

    /**
     * Register the module's routes.
     */
    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));
    }


    /**
     * Check if standalone updater is enabled
     */
    private function isStandaloneUpdaterEnabled(): bool
    {
        if (mw()->ui->disable_marketplace != true) {
            return false;
        }
        if (is_link(mw_root_path() . DS . 'src')) {
            return false;
        }
        if (is_link(mw_root_path() . DS . 'vendor')) {
            return false;
        }
        return true;
    }

    /**
     * Get latest version from the update server
     */
    private function getLatestVersion()
    {
        return cache()->remember('standalone_updater_latest_version', 1440, function () {
            $updateApi = 'http://updater.microweberapi.com/builds/master/version.txt';
            $version = app()->url_manager->download($updateApi);
            if ($version) {
                $version = trim($version);
                return $version;
            }
        });
    }
}
