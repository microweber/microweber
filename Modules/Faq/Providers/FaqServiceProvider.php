<?php

namespace Modules\Faq\Providers;


use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Faq\Filament\Resources\FaqResource;
use Modules\Faq\Models\Faq;

class FaqServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Faq';

    protected string $moduleNameLower = 'faq';

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
       // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));


        // Register filament page for Microweber module settings
       // FilamentRegistry::registerPage(FaqResource::class);

        // Register Filament resource
      //  FilamentRegistry::registerResource(FaqResource::class);

        // Register Microweber module
        //Microweber::module('faq', Faq::class);

    }

}
