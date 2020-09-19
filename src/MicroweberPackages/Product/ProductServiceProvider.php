<?php

namespace MicroweberPackages\Product;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Database\Observers\BaseModelObserver;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Product::observe(BaseModelObserver::class);

        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');
    }

}
