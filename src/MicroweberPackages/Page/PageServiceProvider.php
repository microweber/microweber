<?php

namespace MicroweberPackages\Page;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Database\Observers\BaseModelObserver;
use MicroweberPackages\Page\Http\Livewire\Admin\PagesTable;
use MicroweberPackages\Page\Http\Livewire\Admin\ProductsTable;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Page\Observers\PageObserver;

class PageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Livewire::component('admin-pages-table', PagesTable::class);

        Page::observe(BaseModelObserver::class);
        Page::observe(PageObserver::class);

        View::addNamespace('page', __DIR__ . '/resources/views');

        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    }

}
