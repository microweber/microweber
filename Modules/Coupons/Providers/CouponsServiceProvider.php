<?php

namespace Modules\Coupons\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Country\Repositories\CountryManager;
use Modules\Coupons\Services\CouponService;

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
         /**
         * @property CouponService $coupon_service
         */
        $this->app->bind('coupon_service', function () {
            return new CouponService();
        });


        FilamentRegistry::registerResource(\Modules\Coupons\Filament\Resources\CouponResource::class);
    }

}
