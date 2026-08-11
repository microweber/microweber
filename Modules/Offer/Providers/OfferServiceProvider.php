<?php

namespace Modules\Offer\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\ModuleRegistry\Facades\ModuleRegistry;
use Modules\Offer\Filament\Admin\Resources\OfferResource;
use Modules\Offer\Models\Offer;


class OfferServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Offer';

    protected string $moduleNameLower = 'offer';

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
        parent::register();

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));
        $this->app->register(OfferEventServiceProvider::class);
        /**
         * @property \Modules\Offer\Repositories\OfferRepository $offer_repository
         */
        $this->app->bind('offer_repository', function () {
            return new \Modules\Offer\Repositories\OfferRepository();
        });
        // Register filament page for Microweber module settings
        // FilamentRegistry::registerPage(OfferModuleSettings::class);
        FilamentRegistry::registerResource(OfferResource::class);

        FilamentRegistry::registerGlobalSearchEntry(
            'Offers & Discount Prices', '/admin/offers',
            ['offer', 'offers', 'discount', 'discount price', 'sale price',
             'special offer', 'deal', 'promotion', 'price reduction'],
            'Shop Settings', ['Section' => 'Shop Settings'],
        );

        // Register Microweber module
        // ModuleRegistry::module(\Modules\Offer\Microweber\OfferModule::class);

    }

}
