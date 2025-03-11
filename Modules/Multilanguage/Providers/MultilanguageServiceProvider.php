<?php

namespace Modules\Multilanguage\Providers;

use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Multilanguage\Filament\MultilanguageSettings;
use Modules\Multilanguage\Livewire\LanguagesTable;
use Modules\Multilanguage\Microweber\MultilanguageModule;

class MultilanguageServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Multilanguage';
    protected string $moduleNameLower = 'multilanguage';

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));

        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(MultilanguageSettings::class);

        // Register Microweber module
        Microweber::module(MultilanguageModule::class);
        Livewire::component('modules.multilanguage::languages-table', LanguagesTable::class);

    }

    /**
     * Boot the application events.
     */
    public function boot(): void
    {

        // Register Livewire components

        // Register event bindings
        $this->registerEventBindings();
    }

    /**
     * Register event bindings for the module.
     */
    protected function registerEventBindings(): void
    {
        // Admin header toolbar
        if (function_exists('event_bind')) {
            event_bind('mw.admin.header.toolbar.ul', function () {
                echo '<module type="multilanguage" show_settings_link="true" template="admin"></module>';
            });

            // Live edit toolbar
            event_bind('live_edit_toolbar_action_buttons', function () {
                echo '<module type="multilanguage/change_language"></module>';
            });
        }
    }


}
