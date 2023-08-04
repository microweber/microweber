<?php
namespace MicroweberPackages\CustomField\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\CustomField\Http\Livewire\CustomFieldAddModalComponent;
use MicroweberPackages\CustomField\Http\Livewire\CustomFieldEditModalComponent;
use MicroweberPackages\CustomField\Http\Livewire\CustomFieldsListComponent;
use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\CustomField\Repositories\CustomFieldRepository;
use MicroweberPackages\CustomField\TranslateTables\TranslateCustomFields;
use MicroweberPackages\CustomField\TranslateTables\TranslateCustomFieldsValues;


class CustomFieldServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->loadMigrationsFrom(__DIR__. '/../database/migrations/');

        /**
         * @property CustomFieldRepository $custom_field_repository
         */
        $this->app->bind('custom_field_repository', function () {
            return new CustomFieldRepository();
        });
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        View::addNamespace('custom_field', normalize_path(dirname(__DIR__) . '/resources/views'));

        Livewire::component('custom-fields-list', CustomFieldsListComponent::class);
        Livewire::component('custom-field-edit-modal', CustomFieldEditModalComponent::class);
        Livewire::component('custom-field-add-modal', CustomFieldAddModalComponent::class);

        $this->app->translate_manager->addTranslateProvider(TranslateCustomFields::class);
        $this->app->translate_manager->addTranslateProvider(TranslateCustomFieldsValues::class);

       // CustomField::observe(CreatedByObserver::class);
    }
}
