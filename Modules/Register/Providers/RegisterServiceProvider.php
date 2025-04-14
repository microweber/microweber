<?php

namespace Modules\Register\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Register\Filament\Pages\Admin\AdminRegisterSettingsPage;


class RegisterServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Register';

    protected string $moduleNameLower = 'register';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {


        if (mw_is_installed()) {
            $enable_user_registration = get_option('enable_user_registration', 'users');
            $registration_approval_required = get_option('registration_approval_required', 'users');

            if ($enable_user_registration) {
                Config::set('modules.register.enable_user_registration', $enable_user_registration);
            }
            if ($registration_approval_required) {
                Config::set('modules.register.registration_approval_required', $registration_approval_required);
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
        // $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));

        FilamentRegistry::registerPage(
            AdminRegisterSettingsPage::class,
            \Modules\Settings\Filament\Pages\Settings::class
        );


        // Register filament page for Microweber module settings
        // FilamentRegistry::registerPage(RegisterModuleSettings::class);

        // Register Microweber module
        // Microweber::module(\Modules\Register\Microweber\RegisterModule::class);

    }

}
