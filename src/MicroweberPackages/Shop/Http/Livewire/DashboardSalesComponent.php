<?php

namespace MicroweberPackages\Shop\Http\Livewire;

use Livewire\Component;
use function view;

class DashboardSalesComponent extends Component
{
    public $data = [];

    public function render()
    {
        return view('shop::admin.livewire.dashboard.sales');
    }

    public function loadData()
    {
        $sales = app()->order_repository->getStatsForPeriod();

        $this->data= $sales;
    }
}
