<?php

namespace Modules\Profile\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Profile\Http\Middleware\TwoFactorRateLimit;

class ProfileServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Profile';
    protected string $moduleNameLower = 'profile';

    public function boot(): void
    {
        $this->registerMiddleware();
        $this->registerConfig();
      //  $this->registerUserModel();
        parent::registerTranslations();
        parent::registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));
    }

    protected function registerUserModel(): void
    {
        $this->app->bind(\Illuminate\Contracts\Auth\Authenticatable::class, \Modules\Profile\Models\User::class);
        $this->app->bind(\Illuminate\Contracts\Auth\StatefulGuard::class, function () {
            return auth()->guard('web');
        });
    }

    protected function registerMiddleware(): void
    {
        $this->app['router']->aliasMiddleware(
            '2fa.rate_limit',
            TwoFactorRateLimit::class
        );
    }

    public function registerConfig(): void
    {
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'config/twofactor.php'),
            'profile.twofactor'
        );
    }

    public function register(): void
    {
        $this->registerTranslations();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));

        $this->app->register(FilamentProfilePanelProvider::class);
    }
}
