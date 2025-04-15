<?php

namespace Modules\Faq\Providers;

use Livewire\Livewire;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Faq\Filament\FaqTableList;
use Modules\Faq\Filament\Resources\FaqModuleResource;
use Modules\Faq\Microweber\FaqModule;
use Modules\Settings\Filament\Pages\Settings;

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


        Livewire::component('modules.faq.filament.faq-table-list', FaqTableList::class);



        FilamentRegistry::registerResource(FaqModuleResource::class);
        FilamentRegistry::registerResource(FaqModuleResource::class,Settings::class);

        // Register Microweber module
        Microweber::module(FaqModule::class);
    }
}
