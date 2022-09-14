<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Order\Models\Order;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Product\Models\Product;

class OrdersIndexComponent extends Component
{
    use WithPagination;

    public $paginate = 10;
    protected $paginationTheme = 'bootstrap';

    public $filters = [];
    protected $listeners = [
        'refreshOrderIndexComponent' => '$refresh',
        'setFirstPageOrderIndexComponent' => 'setFirstPagePagination',
    ];
    protected $queryString = ['filters', 'showFilters','paginate'];

    public $showColumns = [
        'id' => true,
        'image' => true,
        'title' => true,
        'price' => true,
        'stock' => true,
        'sales' => true,
        'quantity' => true,
        'author' => false
    ];

    public $showFilters = [];

    public $checked = [];
    public $selectAll = false;

    public function clearFilters()
    {
        $this->filters = [];
    }

    public function deselectAll()
    {
        $this->checked = [];
        $this->selectAll = false;
    }

    public function updatedShowColumns($value)
    {
        \Cookie::queue('orderShowColumns', json_encode($this->showColumns));
    }

    public function updatedShowFilters($value)
    {
        $this->showFilters = array_filter($this->showFilters);
    }

    public function updatedChecked($value)
    {
        if (count($this->checked) == count($this->orders->items())) {
            $this->selectAll = true;
        } else {
            $this->selectAll = false;
        }
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
        $this->checked = $this->orders->pluck('id')->map(fn($item) => (string)$item)->toArray();
    }

    public function multipleDelete()
    {
        $this->emit('multipleDelete', $this->checked);
    }

    public function setFirstPagePagination()
    {
        $this->setPage(1);
    }

    public function render()
    {
        return view('order::admin.orders.livewire.table', [
            'orders' => $this->orders,
            'appliedFilters' => $this->appliedFilters,
            'appliedFiltersFriendlyNames' => $this->appliedFiltersFriendlyNames,
        ]);
    }

    public function getOrdersProperty()
    {
        return $this->ordersQuery->paginate($this->paginate);
    }

    public function removeFilter($key)
    {
        unset($this->filters[$key]);
    }

    public function orderBy($value)
    {
        $this->filters['orderBy'] = $value;
    }

    public function getOrdersQueryProperty()
    {
        $query = Order::query();
        // $query->disableCache(true);

        $this->appliedFilters = [];
        $this->appliedFiltersFriendlyNames = [];
        foreach ($this->filters as $filterKey => $filterValue) {

            if (empty($filterValue)) {
                continue;
            }

            $this->appliedFilters[$filterKey] = $filterValue;
            $filterFriendlyValue = $filterValue;

            if (is_numeric($filterValue)) {
                $filterValue = $filterValue . ',';
            }

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

            if (is_array($filterFriendlyValue)) {
                $filterFriendlyValue = array_filter($filterFriendlyValue);
            }

            $this->appliedFiltersFriendlyNames[$filterKey] = $filterFriendlyValue;
        }

        $query->filter($this->appliedFilters);

        return $query;
    }

    public function mount()
    {
        $columnsCookie = \Cookie::get('orderShowColumns');
        if (!empty($columnsCookie)) {
            $this->showColumns = json_decode($columnsCookie, true);
        }
    }
}

