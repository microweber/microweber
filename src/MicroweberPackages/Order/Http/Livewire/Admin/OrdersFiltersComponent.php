<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin;

use Livewire\Component;

class OrdersFiltersComponent extends Component
{
    public $page;
    public $paginationLimit;
    public $filters = [];

    public $listeners = [
        'setFilterToOrders'=>'setFilter',
        'autoCompleteSelectItem'=>'setFilter',
        'hideFilterItem'=>'hideFilter'
    ];

    protected $queryString = ['filters', 'showFilters', 'page'];

    public $showFilters = [];

    public function refreshOrdersTable()
    {
        $this->dispatch('autocompleteRefresh');
        $this->dispatch('setFiltersToOrders', [
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

    public function updateShowFilters()
    {
        $this->showFilters = array_filter($this->showFilters);
        if (!empty($this->showFilters)) {
            foreach ($this->showFilters as $filterKey=>$filterValue) {
                session()->flash('showFilter' . ucfirst($filterKey), '1');
            }
        }
        $this->dispatch('$refresh')->self();
    }

    public function render()
    {
        return view('order::admin.orders.livewire.filters');
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

    public function hideFilter($key)
    {
        if (isset($this->showFilters[$key])) {
            unset($this->showFilters[$key]);
        }
        if (isset($this->filters[$key])) {
            unset($this->filters[$key]);
        }
    }

    public function orderBy($value)
    {
        $this->filters['orderBy'] = $value;
        $this->refreshOrdersTable();
    }

}

