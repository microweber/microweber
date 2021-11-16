<?php

namespace MicroweberPackages\Page;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Database\Observers\BaseModelObserver;
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
        Page::observe(BaseModelObserver::class);
        Page::observe(PageObserver::class);

        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    }

}
