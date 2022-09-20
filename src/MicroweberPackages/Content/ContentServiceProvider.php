<?php

namespace MicroweberPackages\Content;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Content\TranslateTables\TranslateContent;
use MicroweberPackages\Content\TranslateTables\TranslateContentFields;
use MicroweberPackages\Database\Observers\BaseModelObserver;
use MicroweberPackages\Content\Http\Livewire\ContentBulkOptions;

/**
 * Class ConfigSaveServiceProvider
 * @package MicroweberPackages\Config
 */
class ContentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->translate_manager->addTranslateProvider(TranslateContent::class);
        $this->app->translate_manager->addTranslateProvider(TranslateContentFields::class);

        Content::observe(BaseModelObserver::class);
        //  Content::observe(CreatedByObserver::class);

        Livewire::component('admin-content-bulk-options', ContentBulkOptions::class);
        View::addNamespace('content', __DIR__ . DS . 'resources' . DS . 'views');

        $this->loadMigrationsFrom(__DIR__ . DS . 'migrations');
        $this->loadRoutesFrom(__DIR__ . DS . 'routes' . DS . 'api.php');
        $this->loadRoutesFrom(__DIR__ . DS . 'routes' . DS . 'web.php');
    }
}
