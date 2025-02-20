<?php

namespace Modules\ImageRollover\Providers;

use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\ImageRollover\Filament\ImageRolloverModuleSettings;
use Modules\ImageRollover\Microweber\ImageRolloverModule;

class ImageRolloverServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'ImageRollover';

    protected string $moduleNameLower = 'image_rollover';

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

        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(ImageRolloverModuleSettings::class);
        // Register Microweber module
        Microweber::module(ImageRolloverModule::class);
    }
}
