<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Admin\Http\Livewire\FilterItemMultipleSelectComponent;
use MicroweberPackages\Order\Models\Order;
use MicroweberPackages\User\Models\User;

class FilterItemOrderCustomer extends FilterItemMultipleSelectComponent
{
    public $name = 'Customer';
    public $model = Order::class;
    public $selectedItemKey = 'customer';
    public string $placeholder = 'Type to search by customers...';

    public $firstItemName;
    public $firstTimeLoading = false;

    public $perPage = 10;

    protected function getListeners()
    {
        return array_merge($this->listeners, [
            'filterItemUsersRefresh'=>'$refresh',
            'filterItemUsersResetProperties'=>'resetProperties'
        ]);
    }

    public function loadMore()
    {
        $this->emit('loadMoreExecuted');
        $this->perPage = $this->perPage + 5;
        $this->refreshQueryData();
    }

    public function mount()
    {
        $this->firstTimeLoading = true;
        $this->refreshFirstItemName();
    }

    public function updatedSelectedItems(array $items)
    {
        parent::updatedSelectedItems($items);

        $this->refreshFirstItemName();
    }

    public function refreshFirstItemName()
    {
        if (isset($this->selectedItems[0])) {
            $getItem = $this->model::where('id', $this->selectedItems[0])->first();
            if ($getItem != null) {
                $this->firstItemName = $getItem->displayName();
            }
        }
    }

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
            $this->showDropdown($this->id);
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
