<?php
namespace MicroweberPackages\Modules\Shop\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;
use MicroweberPackages\Product\Models\Product;

class ShopComponent extends ModuleSettingsComponent
{
    public function render()
    {

        $productsQuery = Product::query();
        $productsQuery->where('is_active', 1);

        $products = $productsQuery->paginate(10);

       return view('microweber-module-shop::livewire.shop.index', [
            'products' => $products
       ]);
    }
}
