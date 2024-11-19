<?php

namespace MicroweberPackages\Product;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Database\Observers\BaseModelObserver;
use MicroweberPackages\Product\Http\Livewire\Admin\ProductsList;
use Modules\Product\Models\Product;
use Modules\Product\Validators\PriceValidator;

//use MicroweberPackages\Product\Http\Livewire\Admin\ContentBulkOptions;
//use MicroweberPackages\Product\Http\Livewire\Admin\ProductsTable;
//use MicroweberPackages\Product\Http\Livewire\ProductsAutoComplete;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Livewire::component('admin-products-list', ProductsList::class);
      // Livewire::component('admin-products-autocomplete', ProductsAutoComplete::class);

        Product::observe(BaseModelObserver::class);
      //  Product::observe(ProductObserver::class); ->moved to CustomFieldsTrait

        View::addNamespace('product', __DIR__ . '/resources/views');

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');

    }

}
