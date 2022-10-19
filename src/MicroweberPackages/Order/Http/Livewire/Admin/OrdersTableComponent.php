<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Order\Http\Livewire\Admin\Modals\OrdersBulkDelete;
use MicroweberPackages\Order\Http\Livewire\Admin\Modals\OrdersBulkOrderStatus;
use MicroweberPackages\Order\Http\Livewire\Admin\Modals\OrdersBulkPaymentStatus;
use MicroweberPackages\Order\Models\Order;
use MicroweberPackages\Page\Models\Page;

class OrdersTableComponent extends Component
{
    use WithPagination;

    public $paginationLimit = 10;
    protected $paginationTheme = 'bootstrap';

    public $filters = [];

    protected $queryString = ['page'];

    public $checked = [];
    public $selectAll = false;

    public $showColumns = [
        'id' => true,
        'image' => true,
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

    protected $listeners = [
        'refreshOrdersFilters' => '$refresh',
        'setFiltersToOrders' => 'setFilters',
        'setPaginationLimitToOrders' => 'setPaginationLimit',
        'deleteExecute' => 'deleteExecute',
        'showDeleteModal' => 'showDeleteModal',
        'hideDeleteModal' => 'hideDeleteModal',
        'statusExecute' => 'statusExecute',
        'paymentStatusExecute' => 'paymentStatusExecute',
        'hideFilterItem'=>'hideFilter'
    ];

    public $displayType = 'card';

    public function setDisplayType($type)
    {
        $this->displayType = $type;
        \Cookie::queue('orderDisplayType', $type);
    }

    public function setPaginationLimit($limit)
    {
        $this->paginationLimit = $limit;
    }

    public function updatedPage()
    {
        $this->deselectAll();
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

    public function hideFilter($key)
    {
        if (isset($this->filters[$key])) {
            unset($this->filters[$key]);
        }
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

        $applyFiltersToQuery = $this->filters;

        if (!isset($applyFiltersToQuery['orderBy'])) {
            $applyFiltersToQuery['orderBy'] = 'id,desc';
        }

        $query->filter($applyFiltersToQuery);
        $query->whereHas('cart');

        return $query;
    }

    public function mount()
    {
        $displayType = \Cookie::get('orderDisplayType');
        if (!empty($displayType)) {
            $this->displayType = $displayType;
        }

        $columnsCookie = \Cookie::get('orderShowColumns');
        if (!empty($columnsCookie)) {
            $showColumns = json_decode($columnsCookie, true);
            $this->showColumns = array_merge($this->showColumns, $showColumns);
        }
    }

    public function render()
    {
        return view('order::admin.orders.livewire.table', [
            'orders' => $this->orders,
            'showColumns' => $this->showColumns,
        ]);
    }

}

