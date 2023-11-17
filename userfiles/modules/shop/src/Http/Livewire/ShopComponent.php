<?php
namespace MicroweberPackages\Modules\Shop\Http\Livewire;

use Livewire\WithPagination;
use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;
use MicroweberPackages\Product\Models\Product;

class ShopComponent extends ModuleSettingsComponent
{
    use WithPagination;

    public $keywords;
    public $queryString = ['keywords'];

    public function render()
    {
        $productsQuery = Product::query();
        $productsQuery->where('is_active', 1);

        $filters = [];
        if ($this->keywords) {
            $filters['keyword'] = $this->keywords;
        }

        if (!empty($filters)) {
            $productsQuery->filter($filters);
        }

        $products = $productsQuery->paginate(10);

       return view('microweber-module-shop::livewire.shop.index', [
            'products' => $products
       ]);
    }
}
