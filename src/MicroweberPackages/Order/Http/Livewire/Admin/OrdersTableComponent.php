<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Order\Models\Order;
use MicroweberPackages\Page\Models\Page;

class OrdersTableComponent extends Component
{
    use WithPagination;

    public $paginationLimit = 10;
    protected $paginationTheme = 'bootstrap';

    public $filters = [];
    public $showColumns = [];

    protected $queryString = ['page'];

    protected $listeners = [
        'refreshOrdersFilters' => '$refresh',
        'setFiltersToOrders' => 'setFilters',
        'setPaginationLimitToOrders' => 'setPaginationLimit',
    ];

    public function setPaginationLimit($limit)
    {
        $this->paginationLimit = $limit;
    }

    public function setFilters($data)
    {
        if (isset($data['filters'])) {
            $this->filters = $data['filters'];
        }

        if (isset($data['showColumns'])) {
            $this->showColumns = $data['showColumns'];
        }

        if (isset($data['page'])) {
            $this->setPage($data['page']);
        }

        $this->emitSelf('$refresh');
    }


    public function getOrdersProperty()
    {
        return $this->ordersQuery->paginate($this->paginationLimit);
    }

    public function getOrdersQueryProperty()
    {
        $query = Order::query();
        $query->filter($this->filters);

        return $query;
    }

    public function render()
    {
        return view('order::admin.orders.livewire.table', [
            'orders' => $this->orders,
            'showColumns' => $this->showColumns,
        ]);
    }

}

