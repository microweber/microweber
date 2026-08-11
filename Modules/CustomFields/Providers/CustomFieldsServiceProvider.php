<?php

namespace Modules\CustomFields\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\ModuleRegistry\Facades\ModuleRegistry;
use MicroweberPackages\Multilanguage\TranslateManager;
use Modules\CustomFields\Filament\Admin\ListCustomFields;
use Modules\CustomFields\Filament\CustomFieldsModuleSettings;
use Modules\CustomFields\Repositories\CustomFieldRepository;
use Modules\CustomFields\Services\FieldsManager;
use Modules\CustomFields\TranslateTables\TranslateCustomFields;
use Modules\CustomFields\TranslateTables\TranslateCustomFieldsValues;


class CustomFieldsServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'CustomFields';

    protected string $moduleNameLower = 'custom_fields';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        if(app()->bound('translate_manager')) {
            app()->translate_manager->addTranslateProvider(TranslateCustomFields::class);
            app()->translate_manager->addTranslateProvider(TranslateCustomFieldsValues::class);
        }



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
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));

        /**
         * @property FieldsManager $fields_manager
         */
        $this->app->singleton('fields_manager', function ($app) {
            return new FieldsManager();
        });

        /**
         * @property CustomFieldRepository $custom_field_repository
         */
        $this->app->bind('custom_field_repository', function () {
            return new CustomFieldRepository();
        });
        Livewire::component('admin-list-custom-fields', ListCustomFields::class);


        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(CustomFieldsModuleSettings::class);

        FilamentRegistry::registerGlobalSearchEntry(
            'Custom Fields', '/admin/settings/custom-fields',
            ['custom fields', 'custom field', 'field', 'fields',
             'form fields', 'extra fields', 'meta fields'],
            'Admin Pages', ['Section' => 'Website'],
        );

        // Register Microweber module
        ModuleRegistry::module(\Modules\CustomFields\Microweber\CustomFieldsModule::class);

    }

}
