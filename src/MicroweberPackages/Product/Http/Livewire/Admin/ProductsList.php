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
        'setFirstPageProductsList' => 'setPaginationFirstPage',
        'autoCompleteSelectItem'=>'setFilter',
        'hideFilterItem'=>'hideFilter'
    ];
    protected $queryString = ['filters', 'showFilters','paginate'];

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
    }

    public function setFilter($key, $value)
    {
        if (is_array($value)) {
            $value = implode(',', $value);
        }
        ;
        $this->filters[$key] = $value;
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

    public function updatedShowFilters($value)
    {
        $this->showFilters = array_filter($this->showFilters);
        if (!empty($this->showFilters)) {
            foreach ($this->showFilters as $filterKey=>$filterValue) {
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
            if ($key == 'userIds') {
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

        $whitelistedEmptyKeys = ['inStock', 'orders','qty'];

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

