<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin;

use Livewire\Component;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Page\Models\Page;

class OrdersFiltersComponent extends Component
{
    public $showColumns = [
        'id' => true,
        'products' => true,
        'customer' => true,
        'total_amount' => true,
        'shipping_method' => true,
        'payment_method' => true,
        'payment_status' => true,
        'status' => true,
        'created_at' => false,
        'updated_at' => false,
        'actions' => true
    ];

    public $page;
    public $filters = [];

    protected $queryString = ['filters', 'showFilters', 'page'];

    public $showFilters = [];

    public $checked = [];
    public $selectAll = false;

    public $appliedFilters = [];
    public $appliedFiltersFriendlyNames = [];

    public function refreshOrdersTable()
    {
        $this->emit('setFiltersToOrders', [
            'page' => 1,
            'filters' => $this->filters,
            'showColumns' => $this->showColumns
        ]);
    }

    public function clearFilters()
    {
        $this->filters = [];
        $this->refreshOrdersTable();
    }

    public function updatedFilters()
    {
        $this->refreshOrdersTable();
    }

    public function deselectAll()
    {
        $this->checked = [];
        $this->selectAll = false;
        $this->refreshOrdersTable();
    }

    public function updatedShowColumns($value)
    {
        \Cookie::queue('orderShowColumns', json_encode($this->showColumns));
        $this->refreshOrdersTable();
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
        $this->refreshOrdersTable();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectAll();
        } else {
            $this->deselectAll();
        }
        $this->refreshOrdersTable();
    }

    public function selectAll()
    {
        $this->selectAll = true;
        $this->checked = $this->orders->pluck('id')->map(fn($item) => (string)$item)->toArray();
        $this->refreshOrdersTable();
    }

    public function multipleDelete()
    {
        $this->emit('multipleDelete', $this->checked);
    }

    public function setFirstPagePagination()
    {
        $this->refreshOrdersTable();
    }

    public function render()
    {
        $this->appliedFilters = [];
        $this->appliedFiltersFriendlyNames = [];

        foreach ($this->filters as $filterKey => $filterValue) {

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

        return view('order::admin.orders.livewire.filters', [
            'appliedFilters' => $this->appliedFilters,
            'appliedFiltersFriendlyNames' => $this->appliedFiltersFriendlyNames,
        ]);
    }

    public function removeFilter($key)
    {
        unset($this->filters[$key]);
        $this->refreshOrdersTable();
    }

    public function orderBy($value)
    {
        $this->filters['orderBy'] = $value;
        $this->refreshOrdersTable();
    }

    public function mount()
    {
        $columnsCookie = \Cookie::get('orderShowColumns');
        if (!empty($columnsCookie)) {
            $showColumns = json_decode($columnsCookie, true);
            $this->showColumns = array_merge($this->showColumns, $showColumns);
        }
    }
}

