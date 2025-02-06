<?php

namespace Modules\Faq\Providers;

use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Faq\Filament\Resources\FaqModuleResource;
use Modules\Faq\Microweber\FaqModule;

class FaqServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Faq';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'faq';

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));

        FilamentRegistry::registerResource(FaqModuleResource::class);

        // Register Microweber module
        Microweber::module(FaqModule::class);
    }
}
