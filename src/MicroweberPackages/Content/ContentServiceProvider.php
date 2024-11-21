<?php

namespace MicroweberPackages\Content;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Content\Http\Livewire\Admin\ContentBulkOptions;
use MicroweberPackages\Content\Http\Livewire\Admin\ContentList;
use MicroweberPackages\Database\Observers\BaseModelObserver;
use Modules\Content\TranslateTables\TranslateContent;
use Modules\Content\TranslateTables\TranslateContentFields;

/**
 * Class ConfigSaveServiceProvider
 * @package MicroweberPackages\Config
 */
class ContentServiceProvider extends ServiceProvider
{
    public function register()
    {
     ///   Livewire::component('admin-content-list', ContentList::class);
    //    Livewire::component('admin-content-bulk-options', ContentBulkOptions::class);
    //    View::addNamespace('content', __DIR__ . DS . 'resources' . DS . 'views');

    //    app()->register(ContentFormBuilderServiceProvider::class);

    }
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

      //  \Modules\Content\Models\Content::observe(BaseModelObserver::class);
        //  Content::observe(CreatedByObserver::class);


    }
}
