<?php

namespace Modules\Pdf\Providers;

use MicroweberPackages\Filament\Facades\FilamentRegistry;
use Modules\Pdf\Filament\PdfModuleSettings;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Pdf\Microweber\PdfModule;

class PdfServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Pdf';

    protected string $moduleNameLower = 'pdf';

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
        FilamentRegistry::registerPage(PdfModuleSettings::class);
        Microweber::module('pdf', PdfModule::class);

    }

}
