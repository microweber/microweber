<?php

namespace MicroweberPackages\Product\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Product\Models\Product;

class ProductsIndexComponent extends Component
{
    use WithPagination;

    public $filters = [];
    protected $listeners = [];

    protected $queryString = ['filters'];

    public function clearFilters()
    {
        $this->filters = [];
    }

    public function render()
    {
        $query = Product::query();

        $query->filter($this->filters);

        $products = $query->paginate(10);

        return view('product::admin.product.livewire.table', compact('products'));
    }
}

