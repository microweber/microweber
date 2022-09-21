<?php

namespace MicroweberPackages\Shop\Http\Livewire;

use Illuminate\Support\Carbon;
use Livewire\Component;
use function view;

class DashboardSalesComponent extends Component
{
    public $data = [];
    public $filter = [];
    public $supported_currencies = [];
    public $currency = false;
    protected $listeners = ['changeCurrency' => '$refresh'];

    public function mount()
    {
        if(!isset($this->filter['currency'])){
            $this->filter['currency'] =   $this->currency = app()->shop_manager->get_default_currency();
        }

        $this->filter['from'] = Carbon::parse(strtotime('-2 years'))->format('Y-m-d') . ' 00:00:01';
        $this->filter['to'] = Carbon::parse(strtotime('today'))->format('Y-m-d') . ' 23:59:59';
        $this->supported_currencies = app()->order_repository->getOrderCurrencies();

    }

    public function render()
    {
        return view('shop::admin.livewire.dashboard.sales');
    }


    public function loadSalesData()
    {
   //     $this->emit('initSalesChart');

        $sales = app()->order_repository->getOrdersCountGroupedByDate($this->filter);
        $sum = app()->order_repository->getOrdersTotalSumForPeriod($this->filter);
        $orders_count = app()->order_repository->getOrdersCountForPeriod($this->filter);
        $orders_items_count = app()->order_repository->getOrderItemsCountForPeriod($this->filter);
        $data = [];
        $data['orders_total_amount'] = $sum;
        $data['orders_total_count'] = $orders_count;
        $data['orders_total_items_count'] = $orders_items_count;
        $data['orders_data'] = $sales;
        $this->data = $data;

    }
    public function changeCurrency($currency)
    {
        $this->filter['currency'] = $currency;
        $this->loadSalesData();
    }


}
