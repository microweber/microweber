<?php

namespace MicroweberPackages\Product;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Database\Observers\BaseModelObserver;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\Product\Observers\ProductObserver;

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
      //  Product::observe(ProductObserver::class); ->moved to CustomFieldsTrait

        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
    }

}
