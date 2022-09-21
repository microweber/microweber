<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin;

use MicroweberPackages\Admin\Http\Livewire\AutoCompleteComponent;
use MicroweberPackages\Order\Models\Order;

class OrdersShippingCountryAutoComplete extends AutoCompleteComponent
{
    public $model = Order::class;
    public $selectedItemKey = 'shipping.country';
    public string $placeholder = 'Type to search by country...';

    public $modelGroupByField = 'country';

    public function refreshQueryData()
    {
        $this->closeDropdown();
        if ($this->selectedItem) {
            $this->data = [];
            $this->showDropdown();
            $this->query = $this->selectedItem;
            return;
        }

        $query = $this->model::query();
        $query->select([$this->modelGroupByField]);

        $keyword = trim($this->query);

        if (!empty($keyword)) {
            $query->where($this->modelGroupByField, 'like', '%' . $keyword . '%');
        }

        $query->where(function ($query) {
            $query->where($this->modelGroupByField, '<>', '')->whereNotNull($this->modelGroupByField);
        });

        $query->groupBy($this->modelGroupByField);
        $query->limit(200);

        $get = $query->get();

        if ($get != null) {
            $this->showDropdown();
            $this->data = [];
            foreach ($get as $item) {
                $this->data[] = ['key'=>$item->{$this->modelGroupByField}, 'value'=>$item->{$this->modelGroupByField}];
            }
        }
    }
}
