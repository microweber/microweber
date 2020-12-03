<?php

namespace MicroweberPackages\ContentData\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Database\Observers\CreatedByObserver;
use MicroweberPackages\ContentData\Models\ContentData;

class ContentDataServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       // ContentData::observe(CreatedByObserver::class);
    }

}
