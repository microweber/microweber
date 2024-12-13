<?php

namespace Modules\Offer\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
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
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));

        /**
         * @property \Modules\Offer\Repositories\OfferRepository $offer_repository
         */
        $this->app->bind('offer_repository', function () {
            return new \Modules\Offer\Repositories\OfferRepository();
        });
        // Register filament page for Microweber module settings
        // FilamentRegistry::registerPage(OfferModuleSettings::class);
         FilamentRegistry::registerResource(OfferResource::class);

        // Register Microweber module
        // Microweber::module(\Modules\Offer\Microweber\OfferModule::class);

    }

}
