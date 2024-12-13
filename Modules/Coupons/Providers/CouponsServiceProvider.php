<?php

namespace Modules\Coupons\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Country\Repositories\CountryManager;

class CouponsServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Coupons';

    protected string $moduleNameLower = 'coupons';

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
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));

        FilamentRegistry::registerResource(\Modules\Coupons\Filament\Resources\CouponResource::class);
    }

}
