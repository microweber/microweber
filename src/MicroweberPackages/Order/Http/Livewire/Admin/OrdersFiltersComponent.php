<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Order\Http\Livewire\Admin\Traits\WithColumnsManager;
use MicroweberPackages\Order\Models\Order;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Product\Models\Product;

class OrdersFiltersComponent extends Component
{
    use WithColumnsManager;

    public $page;
    public $filters = [];
    protected $listeners = [
        'refreshOrdersFilters' => '$refresh',
        'setFirstPageToOrders' => 'setFirstPagePagination',
    ];
    protected $queryString = ['filters', 'showFilters', 'page'];

    public $showFilters = [];

    public $checked = [];
    public $selectAll = false;

    public $appliedFilters = [];
    public $appliedFiltersFriendlyNames = [];

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
        return view('order::admin.orders.livewire.filters', [
            'appliedFilters' => $this->appliedFilters,
            'appliedFiltersFriendlyNames' => $this->appliedFiltersFriendlyNames,
        ]);
    }

    public function removeFilter($key)
    {
        unset($this->filters[$key]);
    }

    public function orderBy($value)
    {
        $this->filters['orderBy'] = $value;
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

