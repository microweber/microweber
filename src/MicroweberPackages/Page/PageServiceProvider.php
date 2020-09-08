<?php

namespace MicroweberPackages\Page;

use Illuminate\Support\ServiceProvider;
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
        Page::observe(PageObserver::class);

        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');
    }

}
