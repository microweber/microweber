<?php

namespace MicroweberPackages\ContentData;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Product\Observers\ProductObserver;
use MicroweberPackages\Database\Observers\CreatedByObserver;

class ContentDataServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ContentData::observe(CreatedByObserver::class);
    }

}
