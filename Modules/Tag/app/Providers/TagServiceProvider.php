<?php

namespace Modules\Tag\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Tag\Model\TranslateTaggingTagged;
use Modules\Tag\Model\TranslateTaggingTags;


class TagServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Tag';

    protected string $moduleNameLower = 'tag';


    /**
     * Register the service provider.
     */
    public function register(): void
    {
        // $this->registerTranslations();
        $this->registerConfig();
        //$this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/tagging.php'), 'tagging');


        app()->translate_manager->addTranslateProvider(TranslateTaggingTags::class);
        app()->translate_manager->addTranslateProvider(TranslateTaggingTagged::class);
//
//        /**
//         * @property \Modules\Tag\TagsManager $tags_manager
//         */
//        $this->app->singleton('tags_manager', function ($app) {
//            return new TagsManager();
//        });
    }

}
