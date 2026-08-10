<?php

namespace Modules\Faq\Providers;

use Livewire\Livewire;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Faq\Filament\FaqModuleSettings;
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
        parent::register();

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));


        Livewire::component('modules.faq.filament.faq-table-list', FaqTableList::class);

        FilamentRegistry::registerPage(FaqModuleSettings::class);

        FilamentRegistry::registerResource(FaqModuleResource::class);
        FilamentRegistry::registerResource(FaqModuleResource::class,Settings::class);

        FilamentRegistry::registerGlobalSearchEntry(
            'FAQ Management', '/admin/faqs',
            ['faq', 'frequently asked questions', 'questions', 'answers',
             'help', 'knowledge base', 'q&a'],
            'Admin Pages', ['Section' => 'Website'],
        );

        // Register Microweber module
        Microweber::module(FaqModule::class);
    }
}
