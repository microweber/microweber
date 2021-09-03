<?php
namespace MicroweberPackages\CustomField\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\CustomField\TranslateTables\TranslateCustomFields;
use MicroweberPackages\CustomField\TranslateTables\TranslateCustomFieldsValues;


class CustomFieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->translate_manager->addTranslateProvider(TranslateCustomFields::class);
        $this->app->translate_manager->addTranslateProvider(TranslateCustomFieldsValues::class);

       // CustomField::observe(CreatedByObserver::class);
    }
}
