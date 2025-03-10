<?php

namespace Modules\Tag\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Tag\Filament\Resources\TagGroupResource;
use Modules\Tag\Filament\Resources\TaggedResource;
use Modules\Tag\Filament\Resources\TagResource;
use Modules\Tag\Filament\TagsModuleSettings;
use Modules\Tag\Microweber\TagsModule;
use Modules\Tag\Models\TranslateTaggingTagged;
use Modules\Tag\Models\TranslateTaggingTags;


class TagServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Tag';

    protected string $moduleNameLower = 'tag';


    public function boot(): void
    {


        app()->translate_manager->addTranslateProvider(TranslateTaggingTags::class);
        app()->translate_manager->addTranslateProvider(TranslateTaggingTagged::class);
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/tagging.php'), 'tagging');
        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(TagsModuleSettings::class);
        
        // Register Filament resources
        FilamentRegistry::registerResource(TagResource::class);
        FilamentRegistry::registerResource(TagGroupResource::class);
        FilamentRegistry::registerResource(TaggedResource::class);

        // Register Microweber module
        Microweber::module(TagsModule::class);

    }



}
