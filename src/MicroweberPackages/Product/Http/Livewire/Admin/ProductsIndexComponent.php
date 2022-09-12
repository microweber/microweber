<?php

namespace MicroweberPackages\Product\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Product\Models\Product;

class ProductsIndexComponent extends Component
{
    use WithPagination;

    public $filters = [];
    public $perPage = 10;
    protected $listeners = [];
    protected $queryString = ['filters'];

    public function clearFilters()
    {
        $this->filters = [];
    }

    public function render()
    {
        $query = Product::query();
        $query->disableCache(true);

        $query->filter($this->filters);

        $products = $query->paginate($this->perPage);

        return view('product::admin.product.livewire.table', compact('products'));
    }
}

