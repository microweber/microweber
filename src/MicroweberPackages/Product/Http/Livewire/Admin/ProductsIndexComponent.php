<?php

namespace MicroweberPackages\Product\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Product\Models\Product;

class ProductsIndexComponent extends Component
{
    use WithPagination;
    public $paginate = 10;

    public $filters = [];
    protected $listeners = [];
    protected $queryString = ['filters'];


    public $checked = [];
    public $selectProduct = false;
    public $selectAll = false;

    public function clearFilters()
    {
        $this->filters = [];
    }

    public function updatedSelectProduct($value)
    {
        if ($value) {
            $this->checked = $this->products->pluck('id')->map(fn ($item) => (string) $item)->toArray();
        } else {
            $this->checked = []; 
        }
    }

    public function updatedChecked()
    {
        $this->selectProduct = false;
    }

    public function selectAll()
    {
        $this->selectAll = true;
        $this->checked = $this->products->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }

    public function render()
    {
        return view('product::admin.product.livewire.table', [
            'products'=>$this->products,
            'appliedFiltersFriendlyNames'=>[],
        ]);
    }

    public function getProductsProperty()
    {
        return $this->productsQuery->paginate($this->paginate);
    }

    public function getProductsQueryProperty()
    {
        $query = Product::query();
        $query->disableCache(true);

        $appliedFilters = [];
        $appliedFiltersFriendlyNames = [];
        foreach ($this->filters as $filterKey=>$filterValue) {
            if (empty($filterValue)) {
                continue;
            }
            $appliedFilters[$filterKey] = $filterValue;


            $filterFriendlyValue = $filterValue;

            if (is_string($filterValue)) {
                if (strpos($filterValue, ',') !== false) {
                    $filterValueExp = explode(',', $filterValue);
                    if (!empty($filterValueExp)) {
                        $filterFriendlyValue = [];
                        foreach ($filterValueExp as $resourceId) {

                            if ($filterKey == 'page') {
                                $resourceId = intval($resourceId);
                                $getPage = Page::where('id', $resourceId)->first();
                                if ($getPage != null) {
                                    $filterFriendlyValue[] = $getPage->title;
                                }
                            } else if ($filterKey == 'category') {
                                $resourceId = intval($resourceId);
                                $getCategory = Category::where('id', $resourceId)->first();
                                if ($getCategory != null) {
                                    $filterFriendlyValue[] = $getCategory->title;
                                }
                            } else {
                                $filterFriendlyValue[] = $resourceId;
                            }

                        }
                    }
                }
            }

            $appliedFiltersFriendlyNames[$filterKey] = $filterFriendlyValue;
        }

        $query->filter($appliedFilters);

        return $query;
    }
}

