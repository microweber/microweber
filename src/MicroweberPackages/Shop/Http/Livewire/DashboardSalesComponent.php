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
        $this->data= [1,2,3];
    }
}
