<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin;

use MicroweberPackages\Admin\Http\Livewire\FilterItemComponent;
use Modules\Order\Models\Order;

class FilterItemOrderCustomer extends FilterItemComponent
{
    public $name = 'Customer';
    public $model = Order::class;
    public $selectedItemKey = 'customer';
    public string $placeholder = 'Type to search by customers...';
    public $perPage = 10;

    protected function getListeners()
    {
        return array_merge($this->listeners, [
            'filterItemCustomerRefresh'=>'$refresh',
            'filterItemCustomerResetProperties'=>'resetProperties'
        ]);
    }

    public function loadMore()
    {
        $this->dispatch('loadMoreExecuted');
        $this->perPage = $this->perPage + 5;
        $this->refreshQueryData();
    }

    public function refreshQueryData()
    {
        if (!empty($this->selectedItem)) {
            $json = @json_decode($this->selectedItem, true);
            if (!empty($json)) {
                if (isset($json['first_name'])) {
                    $this->selectedItemText = $json['first_name'] .' '. $json['last_name'];
                }
            }
        }

        $query = $this->model::query();
        $keyword = trim($this->query);

        if (!empty($keyword)) {
            $query->where('first_name', 'like', '%' . $keyword . '%');
            $query->orWhere('last_name', 'like', '%' . $keyword . '%');
            $query->orWhere('email', 'like', '%' . $keyword . '%');
        }

        $query->groupBy('email');

        $this->total = $query->get()->count();

        $query->limit($this->perPage);
        $get = $query->get();

        if ($get != null) {

            $this->data = [];
            foreach ($get as $item) {
                $key = [
                    'email'=>$item->email,
                    'first_name'=>$item->first_name,
                    'last_name'=>$item->last_name,
                    'customer_id'=>$item->customer_id,
                ];
                $key = array_filter($key);
                $key = json_encode($key);

                $this->data[] = ['key'=>$key, 'value'=>$item->first_name . ' ' . $item->last_name];
            }
        }
    }
}
