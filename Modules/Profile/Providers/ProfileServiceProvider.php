<?php

namespace Modules\Profile\Providers;

use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Profile\Http\Middleware\TwoFactorRateLimit;
use Modules\Profile\Livewire\AuthModal;

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
        parent::register();

        $this->registerMiddleware();
        $this->registerConfig();
        $this->registerTranslations();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));

        $this->app->register(FilamentProfilePanelProvider::class);

        // Frontend login/register component mounted in the header account
        // dropdown's #loginModal (replaces the dead `users/login` module).
        Livewire::component('modules.profile.livewire.auth-modal', AuthModal::class);
    }
}
