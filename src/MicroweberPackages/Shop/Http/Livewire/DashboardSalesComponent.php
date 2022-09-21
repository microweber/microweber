<?php

namespace MicroweberPackages\Shop\Http\Livewire;

use Illuminate\Support\Carbon;
use Livewire\Component;
use function view;

class DashboardSalesComponent extends Component
{
    public $data = [];
    public $filters = [];
    public $supported_currencies = [];
    public $currency = false;
    protected $listeners = ['changeCurrency' => '$refresh'];
    protected $queryString = ['filters'];

    public function mount()
    {
        if(!isset($this->filters['currency'])){
            $this->filters['currency'] =   $this->currency = app()->shop_manager->get_default_currency();
        }

        $this->filters['from'] = Carbon::parse(strtotime('-2 years'))->format('Y-m-d');
        $this->filters['to'] = Carbon::parse(strtotime('today'))->format('Y-m-d');
        $this->supported_currencies = app()->order_repository->getOrderCurrencies();

    }

    public function render()
    {
        return view('shop::admin.livewire.dashboard.sales');
    }


    public function loadSalesData()
    {
   //     $this->emit('initSalesChart');

        $sales = app()->order_repository->getOrdersCountGroupedByDate($this->filters);
        $sum = app()->order_repository->getOrdersTotalSumForPeriod($this->filters);
        $orders_count = app()->order_repository->getOrdersCountForPeriod($this->filters);
        $orders_items_count = app()->order_repository->getOrderItemsCountForPeriod($this->filters);
        $data = [];
        $data['orders_total_amount'] = $sum;
        $data['orders_total_count'] = $orders_count;
        $data['orders_total_items_count'] = $orders_items_count;
        $data['orders_data'] = $sales;
        $this->data = $data;

    }
    public function changeCurrency($currency)
    {
        $this->filters['currency'] = $currency;
        $this->loadSalesData();
    }

    public function updatedFilters($filters)
    {
         $this->loadSalesData();
    }


}
