<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin;

use Livewire\Component;
use MicroweberPackages\Order\Models\Order;

class OrdersCustomerAutoComplete extends Component
{
    public $query;
    public $data;
    public $createdById;

    public function mount()
    {
        $this->resetProperties();
    }

    public function render()
    {
        return view('order::admin.orders.livewire.customer-auto-complete');
    }

    public function resetProperties()
    {
        $this->query = '';
        $this->data = [];
    }

    public function selectCreatedById(int $id)
    {
        $this->createdById = $id;
        $this->refreshQueryData();
        $this->emitSelf('$refresh');

        $this->emit('setFilterToOrders', 'customerId',$id);
    }

    public function updatedQuery()
    {
        $this->createdById = false;
        $this->refreshQueryData();
    }

    public function refreshQueryData()
    {
        $query = Order::query();

        if ($this->createdById > 0) {
            $query->where('created_by', $this->createdById);
            $query->limit(1);
            $get = $query->first();
            if ($get != null) {
                $this->data = [];
                $this->query = $get->first_name . ' '. $get->last_name . ' (#'.$get->id.')';
            }
            return;
        }

        $keyword = trim($this->query);

        if (!empty($keyword)) {
            $query->where('first_name', 'like', '%' . $keyword . '%');
            $query->orWhere('last_name', 'like', '%' . $keyword . '%');
            $query->orWhere('email', 'like', '%' . $keyword . '%');
        }

        $query->limit(30);

        $query->groupBy('created_by');

        $get = $query->get();

        if ($get != null) {
            $this->data = $get->toArray();
        }
    }
}
