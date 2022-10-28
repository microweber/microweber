<?php

namespace MicroweberPackages\Product\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\User\Models\User;

class ProductsList extends Component
{
    use WithPagination;

    public $paginate = 10;
    protected $paginationTheme = 'bootstrap';

    public $filters = [];
    protected $listeners = [
        'refreshProductsList' => '$refresh',
        'refreshProductsListAndDeselectAll' => 'refreshProductsListAndDeselectAll',
        'setFirstPageProductsList' => 'setPaginationFirstPage',
        'autoCompleteSelectItem' => 'setFilter',
        'hideFilterItem' => 'hideFilter',
        'applyFilterItem' => 'applyFilterItem',
        'resetFilter' => 'clearFilters',
        'showTrashed' => 'showTrashed',
        'showFromCategory' => 'showFromCategory',
        'showFromPage' => 'showFromPage',
        'deselectAll' => 'deselectAll',
    ];
    protected $queryString = ['filters', 'showFilters', 'paginate'];

    public $showColumns = [
        'id' => true,
        'image' => true,
        'title' => true,
        'price' => true,
        'stock' => true,
        'orders' => true,
        'quantity' => true,
        'author' => false
    ];

    public $showFilters = [];

    public $checked = [];
    public $selectAll = false;

    public function clearFilters()
    {
        $this->filters = [];
        $this->showFilters = [];
        $this->setPaginationFirstPage();
    }

    public function setFilter($key, $value)
    {
        if (is_array($value)) {
            $value = implode(',', $value);
        };
        $this->filters[$key] = $value;
    }

    public function refreshProductsListAndDeselectAll()
    {
        $this->deselectAll();
        $this->emit('refreshProductsList');


    }
    public function deselectAll()
    {
        $this->checked = [];
        $this->selectAll = false;
    }

    public function updatedShowColumns($value)
    {
        \Cookie::queue('productShowColumns', json_encode($this->showColumns));
    }

    public function hideFilter($key)
    {
        if (isset($this->showFilters[$key])) {
            unset($this->showFilters[$key]);
        }
        if (isset($this->filters[$key])) {
            unset($this->filters[$key]);
        }
    }

    public function applyFilterItem($filter, $filterValue)
    {
        $this->removeTrashedFilter();
        $this->filters[$filter] = $filterValue;
        $this->showFilters[$filter] = true;

    }

    public function removeTrashedFilter()
    {
        if (isset($this->filters['trashed'])) {
            unset($this->filters['trashed']);
        }
        if (isset($this->showFilters['trashed'])) {
            unset($this->showFilters['trashed']);
        }

    }

    public function showFromPage($pageId)
    {
        $this->removeTrashedFilter();
        if (isset($this->filters['category'])) {
            unset($this->filters['category']);
        }
        if (isset($this->showFilters['category'])) {
            unset($this->showFilters['category']);
        }
        $this->filters['page'] = $pageId;
        $this->setPaginationFirstPage();
    }


    public function showFromCategory($categoryId)
    {
        $this->removeTrashedFilter();
        if (isset($this->filters['page'])) {
            unset($this->filters['page']);
        }
        if (isset($this->showFilters['page'])) {
            unset($this->showFilters['page']);
        }


        $this->filters['category'] = $categoryId;
        $this->setPaginationFirstPage();
    }


    public function showTrashed($showTrashed = false)
    {
        $this->filters['trashed'] = $showTrashed;
        $this->showFilters['trashed'] = true;
        $this->setPaginationFirstPage();
    }

    public function updatedShowFilters($value)
    {
        $this->showFilters = array_filter($this->showFilters);
        if (!empty($this->showFilters)) {
            foreach ($this->showFilters as $filterKey => $filterValue) {
                session()->flash('showFilter' . ucfirst($filterKey), '1');
            }
        }
    }

    public function updatedChecked($value)
    {
        if (count($this->checked) == count($this->products->items())) {
            $this->selectAll = true;
        } else {
            $this->selectAll = false;
        }
    }

    public function updatedPaginate($limit)
    {
        $this->setPaginationFirstPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectAll();
        } else {
            $this->deselectAll();
        }
    }

    public function selectAll()
    {
        $this->selectAll = true;
        $this->checked = $this->products->pluck('id')->map(fn($item) => (string)$item)->toArray();
    }

    public function multipleMoveToCategory()
    {
        $this->emit('multipleMoveToCategory', $this->checked);
    }

    public function multiplePublish()
    {
        $this->emit('multiplePublish', $this->checked);
    }

    public function multipleUnpublish()
    {
        $this->emit('multipleUnpublish', $this->checked);
    }

    public function multipleDelete()
    {
        $this->emit('multipleDelete', $this->checked);
    }

    public function multipleUndelete()
    {
        $this->emit('multipleUndelete', $this->checked);
    }
   public function multipleDeleteForever()
    {
        $this->emit('multipleDeleteForever', $this->checked);
    }

    public function setPaginationFirstPage()
    {
        $this->setPage(1);
    }

    public function render()
    {
        return view('product::admin.product.livewire.table', [
            'products' => $this->products,
            'appliedFilters' => $this->appliedFilters
        ]);
    }

    public function getProductsProperty()
    {
        return $this->productsQuery->paginate($this->paginate);
    }

    public function removeFilter($key)
    {
        if (isset($this->filters[$key])) {
            if ($key == 'tags') {
                $this->emit('tagsResetProperties');
            }
            if ($key == 'userId') {
                $this->emit('usersResetProperties');
            }
            unset($this->filters[$key]);
        }
    }

    public function orderBy($value)
    {
        $this->filters['orderBy'] = $value;
    }

    public function getProductsQueryProperty()
    {
        $query = Product::query();
        $query->disableCache(true);

        $this->appliedFilters = [];

        $whitelistedEmptyKeys = ['inStock', 'orders', 'qty'];

        foreach ($this->filters as $filterKey => $filterValue) {

            if (!in_array($filterKey, $whitelistedEmptyKeys)) {
                if (empty($filterValue)) {
                    continue;
                }
            }

            $this->appliedFilters[$filterKey] = $filterValue;
        }

        $applyFiltersToQuery = $this->appliedFilters;
        if (!isset($applyFiltersToQuery['orderBy'])) {
            $applyFiltersToQuery['orderBy'] = 'position,desc';
        }
        if (!isset($applyFiltersToQuery['trashed'])) {
            $applyFiltersToQuery['trashed'] = 0;
        }

        $query->filter($applyFiltersToQuery);

        return $query;
    }

    public function mount()
    {
        $columnsCookie = \Cookie::get('productShowColumns');
        if (!empty($columnsCookie)) {
            $this->showColumns = json_decode($columnsCookie, true);
        }
    }
}

