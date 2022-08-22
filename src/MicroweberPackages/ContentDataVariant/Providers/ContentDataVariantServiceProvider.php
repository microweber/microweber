<?php

namespace MicroweberPackages\ContentDataVariant\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\ContentData\TranslateTables\TranslateContentData;

class ContentDataVariantServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . DS . 'migrations');
    }

}
