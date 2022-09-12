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

    public $filters = [];
    public $perPage = 10;
    protected $listeners = [];
    protected $queryString = ['filters'];

    public $selectAll = false;
    public $selectedIds = [];

    public function clearFilters()
    {
        $this->filters = [];
    }

    public function mount()
    {
        $this->selectedIds = collect();
    }

    public function render()
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

        $products = $query->paginate($this->perPage);

        return view('product::admin.product.livewire.table', compact('products', 'appliedFiltersFriendlyNames'));
    }
}

