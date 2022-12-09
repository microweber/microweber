<?php

namespace MicroweberPackages\Content;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Content\Http\Livewire\Admin\ContentBulkOptions;
use MicroweberPackages\Content\TranslateTables\TranslateContent;
use MicroweberPackages\Content\TranslateTables\TranslateContentFields;
use MicroweberPackages\Database\Observers\BaseModelObserver;

/**
 * Class ConfigSaveServiceProvider
 * @package MicroweberPackages\Config
 */
class ContentServiceProvider extends ServiceProvider
{
    public function register()
    {
        Livewire::component('admin-content-bulk-options', ContentBulkOptions::class);
        View::addNamespace('content', __DIR__ . DS . 'resources' . DS . 'views');

        $this->loadMigrationsFrom(__DIR__ . DS . 'migrations');
        $this->loadRoutesFrom(__DIR__ . DS . 'routes' . DS . 'api.php');
        $this->loadRoutesFrom(__DIR__ . DS . 'routes' . DS . 'web.php');
    }
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->translate_manager->addTranslateProvider(TranslateContent::class);
        $this->app->translate_manager->addTranslateProvider(TranslateContentFields::class);

        \MicroweberPackages\Content\Models\Content::observe(BaseModelObserver::class);
        //  Content::observe(CreatedByObserver::class);


    }
}
