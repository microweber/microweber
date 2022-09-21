<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin;

use MicroweberPackages\Admin\Http\Livewire\AutoCompleteComponent;
use MicroweberPackages\Order\Models\Order;

class OrdersCustomersAutoComplete extends AutoCompleteComponent
{
    public $model = Order::class;
    public $selectedItemKey = 'customer';
    public string $placeholder = 'Type to search customers...';

    public function refreshQueryData()
    {
        $this->closeDropdown();

        $query = $this->model::query();

       /* if ($this->selectedItem > 0) {
            $query->where('id', $this->selectedItem);
            $query->limit(1);
            $get = $query->first();
            if ($get != null) {
                $this->data = [];
                $this->showDropdown = true;
                $this->query = $get->displayName() . ' (#'.$get->id.')';
            }
            return;
        }*/

        $keyword = trim($this->query);

        if (!empty($keyword)) {
            $query->where('first_name', 'like', '%' . $keyword . '%');
            $query->orWhere('last_name', 'like', '%' . $keyword . '%');
            $query->orWhere('email', 'like', '%' . $keyword . '%');
        }

        $query->groupBy('email');
        $query->limit(200);

        $get = $query->get();

        if ($get != null) {
            $this->showDropdown();
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
