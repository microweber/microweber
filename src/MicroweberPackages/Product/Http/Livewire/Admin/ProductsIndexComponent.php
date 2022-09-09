<?php

namespace MicroweberPackages\Product\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Product\Models\Product;

class ProductsIndexComponent extends Component
{
    use WithPagination;

    protected $listeners = [];

    public function render()
    {
        $products = Product::paginate(10);

        return view('product::admin.product.livewire.table', compact('products'));
    }
}

