<?php
namespace MicroweberPackages\CustomField\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\CustomField\Repositories\CustomFieldRepository;
use MicroweberPackages\CustomField\TranslateTables\TranslateCustomFields;
use MicroweberPackages\CustomField\TranslateTables\TranslateCustomFieldsValues;


class CustomFieldServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->resolving(\MicroweberPackages\Repository\RepositoryManager::class, function (\MicroweberPackages\Repository\RepositoryManager $repositoryManager) {
            $repositoryManager->extend(CustomField::class, function () {
                return new CustomFieldRepository();
            });
        });

        /**
         * @property CustomFieldRepository   $custom_field_repository
         */
        $this->app->bind('custom_field_repository', function () {
            return $this->app->custom_field_repository->driver(CustomField::class);;
        });
    }

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
