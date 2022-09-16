<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Order\Http\Livewire\Admin\Traits\WithColumnsManager;
use MicroweberPackages\Order\Models\Order;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Product\Models\Product;

class OrdersTableComponent extends Component
{
    use WithPagination;
    use WithColumnsManager;

    public $paginate = 10;
    protected $paginationTheme = 'bootstrap';

    public $filters = [];

    protected $queryString = ['page'];

    protected $listeners = [
        'refreshOrdersFilters' => '$refresh',
        'setFiltersToOrders' => 'setFilters',
        'setFirstPageToOrders' => 'setFirstPagePagination',
    ];

    public function setFilters($data)
    {
        if (isset($data['filters'])) {
            $this->filters = $data['filters'];
        }
        $this->emitSelf('$refresh');
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
            $showColumns = json_decode($columnsCookie, true);
            $this->showColumns = array_merge($this->showColumns, $showColumns);
        }
    }
}

