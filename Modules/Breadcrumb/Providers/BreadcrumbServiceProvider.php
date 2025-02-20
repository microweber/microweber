<?php

namespace Modules\Breadcrumb\Providers;

use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Breadcrumb\Filament\BreadcrumbModuleSettings;
use Modules\Breadcrumb\Microweber\BreadcrumbModule;

class BreadcrumbServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Breadcrumb';
    protected string $moduleNameLower = 'breadcrumb';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();

        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(BreadcrumbModuleSettings::class);

        // Register Microweber module
        Microweber::module(BreadcrumbModule::class);
    }
}
