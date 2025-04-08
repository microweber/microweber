<?php

namespace Modules\Content\Providers;

use Livewire\Livewire;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Microweber\Facades\Microweber;
use MicroweberPackages\Multilanguage\TranslateManager;
use Modules\Content\Filament\Admin\ContentResource;
use Modules\Content\Filament\ContentModuleSettings;
use Modules\Content\Filament\ContentTableList;
use Modules\Content\Microweber\ContentModule;
use Modules\Content\Models\Content;
use Modules\Content\Observers\ContentObserver;
use Modules\Content\Services\ContentManager;
use Modules\Content\Repositories\ContentRepository;
use Modules\Content\TranslateTables\TranslateContent;
use Modules\Content\TranslateTables\TranslateContentFields;

class ContentServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Content';

    protected string $moduleNameLower = 'content';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        if(app()->bound('translate_manager')) {
            app()->translate_manager->addTranslateProvider(TranslateContent::class);
            app()->translate_manager->addTranslateProvider(TranslateContentFields::class);
        }

        Content::observe(ContentObserver::class);


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
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));




        /**
         * @property \Modules\Content\Repositories\ContentRepository   $content_repository
         */
        $this->app->bind('content_repository', function ($app) {
            return new ContentRepository();
        });


        /**
         * @property \Modules\Content\Services\ContentManager    $content_manager
         */
        $this->app->singleton('content_manager', function ($app) {
            return new ContentManager();
        });



        Livewire::component('modules.content.filament.content-table-list', ContentTableList::class);
        FilamentRegistry::registerResource(ContentResource::class);
        FilamentRegistry::registerPage(ContentModuleSettings::class);
        Microweber::module(ContentModule::class);




        event_bind('mw.front', function ($params = false) {
            template_foot(function () {

                $contentId = content_id();
                if ($contentId) {
                    $graph = new \Spatie\SchemaOrg\Graph();
                    $graphFilled = getSchemaOrgContentFilled($graph, $contentId);

                    if ($graphFilled) {
                        return $graphFilled->toScript();
                    }
                }

            });
        });


    }
}
