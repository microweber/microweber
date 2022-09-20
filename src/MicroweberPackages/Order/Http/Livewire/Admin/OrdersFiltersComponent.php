<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin;

use Livewire\Component;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Page\Models\Page;

class OrdersFiltersComponent extends Component
{
    public $page;
    public $paginationLimit;
    public $filters = [];

    public $listeners = [
        'setFilterToOrders'=>'setFilter',
        'autoCompleteSelectItem'=>'setFilter'
    ];

    protected $queryString = ['filters', 'showFilters', 'page'];

    public $showFilters = [];

    public $appliedFilters = [];
    public $appliedFiltersFriendlyNames = [];

    public function refreshOrdersTable()
    {
        $this->emit('setFiltersToOrders', [
            'page' => 1,
            'filters' => $this->filters
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

    public function updatedShowFilters($value)
    {
        $this->showFilters = array_filter($this->showFilters);
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

    public function setFilter($key, $value)
    {
        $this->filters[$key] = $value;
        $this->refreshOrdersTable();
    }

    public function orderBy($value)
    {
        $this->filters['orderBy'] = $value;
        $this->refreshOrdersTable();
    }

}

