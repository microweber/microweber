<?php

namespace Modules\FacebookLike\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\FacebookLike\Filament\FacebookLikeModuleSettings;
use Modules\FacebookLike\Microweber\FacebookLikeModule;

class FacebookLikeServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'FacebookLike';

    protected string $moduleNameLower = 'facebook_like';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {

        // Load module routes
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

        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(FacebookLikeModuleSettings::class);

        // Register Microweber module
        Microweber::module(FacebookLikeModule::class);
    }
}
