<?php

namespace Modules\Login\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Login\Filament\Pages\Admin\AdminLoginSettingsPage;
use Modules\Register\Filament\Pages\Admin\AdminRegisterSettingsPage;


class LoginServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Login';

    protected string $moduleNameLower = 'login';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {


        if (mw_is_installed()) {
            $disable_login = get_option('disable_login', 'users');

            if ($disable_login) {
                Config::set('modules.login.disable_login', $disable_login);
            }


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
        //$this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));

        FilamentRegistry::registerPage(
            AdminLoginSettingsPage::class,
         );

        FilamentRegistry::registerPage(
            AdminLoginSettingsPage::class,
         \Modules\Settings\Filament\Pages\Settings::class
        );
        // Register filament page for Microweber module settings
        // FilamentRegistry::registerPage(LoginModuleSettings::class);

        // Register Microweber module
        // Microweber::module(\Modules\Login\Microweber\LoginModule::class);

    }

}
