<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin;

use MicroweberPackages\Admin\Http\Livewire\AutoCompleteComponent;
use MicroweberPackages\Order\Models\Order;

class OrdersShippingCountryAutoComplete extends AutoCompleteComponent
{
    public $model = Order::class;
    public $selectedItemKey = 'shipping.country';
    public string $placeholder = 'Type to search country...';

    public function refreshQueryData()
    {
        $this->closeDropdown();

        $query = $this->model::query();

        if ($this->selectedItem) {
            $this->data = [];
            $this->showDropdown();
            $this->query = $this->selectedItem;
            return;
        }

        $keyword = trim($this->query);

        if (!empty($keyword)) {
            $query->where('country', 'like', '%' . $keyword . '%');
        }

        $query->where(function ($query) {
            $query->where('country', '<>', '')->whereNotNull('country');
        });

        $query->groupBy('country');
        $query->limit(200);

        $get = $query->get();

        if ($get != null) {
            $this->showDropdown();
            $this->data = [];
            foreach ($get as $item) {
                $this->data[] = ['key'=>$item->country, 'value'=>$item->country];
            }
        }
    }
}
