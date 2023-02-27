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
     public $view = [];
    public $currency = false;
    protected $listeners = [
        'changeCurrency' => '$refresh',
        'autoCompleteSelectItem' => 'setFilter',
   //     'refreshQueryData' => '$refresh',
      //  'updatedQuery' => '$refresh',
    ];
    protected $queryString = ['filters'];

    public function mount()
    {
        if(!isset($this->filters['currency'])){
            $this->filters['currency'] =   $this->currency = app()->shop_manager->get_default_currency();
        }

        $this->filters['from'] = Carbon::parse(strtotime('-2 months'))->format('Y-m-d');
        $this->filters['to'] = Carbon::parse(strtotime('today'))->format('Y-m-d');
        $this->view['supported_currencies'] = app()->order_repository->getOrderCurrencies();
        $this->view['show_period_range'] = $this->view['show_period_range'] ?? 'daily';
        $this->view['supported_period_ranges'] = ['daily', 'weekly', 'monthly', 'yearly'];

    }


    public function render()
    {
        return view('shop::admin.livewire.dashboard.sales');
    }


    public function loadSalesData()
    {
   //     $this->emit('initSalesChart');

        // this is used to refresh the chart
        $filters = $this->filters;
        if(isset($this->view['show_period_range'])){
            $filters['period_group'] = $this->view['show_period_range'];
         }

        $sales = app()->order_repository->getOrdersCountGroupedByDate($filters);



        $sum = app()->order_repository->getOrdersTotalSumForPeriod($this->filters);
        $orders_count = app()->order_repository->getOrdersCountForPeriod($this->filters);
        $orders_items_count = app()->order_repository->getOrderItemsCountForPeriod($this->filters);
         $orders_best_selling_products = app()->order_repository->getBestSellingProductsForPeriod($this->filters);
        $orders_best_selling_categories = app()->order_repository->getBestSellingCategoriesForPeriod($this->filters);

        $data = [];
        $data['orders_best_selling_categories'] = $orders_best_selling_categories;
        $data['orders_best_selling_products'] = $orders_best_selling_products;
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
  public function changePeriodDateRangeType($type)
    {
        $this->view['show_period_range'] = $type;
        $this->loadSalesData();
    }

    public function updatedFilters($filters)
    {
         $this->loadSalesData();
    }
    public function removeFilter($key)
    {
        unset($this->filters[$key]);
        $this->loadSalesData();
    }

    public function setFilter($key, $value)
    {
        $this->filters[$key] = $value;
        $this->loadSalesData();
    }


}
