<?php

namespace Modules\Profile\Providers;

use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Profile\Http\Middleware\TwoFactorRateLimit;

class ProfileServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Profile';
    protected string $moduleNameLower = 'profile';

    public function boot(): void
    {



    }



    protected function registerMiddleware(): void
    {
        $this->app['router']->aliasMiddleware(
            '2fa.rate_limit',
            TwoFactorRateLimit::class
        );
    }


    public function register(): void
    {
        $this->registerMiddleware();
        $this->registerConfig();
        $this->registerTranslations();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));

        $this->app->register(FilamentProfilePanelProvider::class);


    }
}
