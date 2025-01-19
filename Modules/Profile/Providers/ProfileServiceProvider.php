<?php

namespace Modules\Profile\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Checkout\Livewire\CartItems;
use Modules\Checkout\Livewire\ReviewOrder;
use Modules\Checkout\Providers\FilamentCheckoutPanelProvider;


class ProfileServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Profile';
    protected string $moduleNameLower = 'profile';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {


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
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));


        $this->app->register(FilamentProfilePanelProvider::class);




    }

}
