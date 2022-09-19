<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin;

use Livewire\Component;
use MicroweberPackages\Order\Models\Order;
use MicroweberPackages\User\Models\User;

class OrdersUsersAutoComplete extends Component
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
        return view('order::admin.orders.livewire.user-auto-complete');
    }

    public function resetProperties()
    {
        $this->query = '';
        $this->data = false;
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
        $query = User::query();

        if ($this->createdById > 0) {
            $query->where('id', $this->createdById);
            $query->limit(1);
            $get = $query->first();
            if ($get != null) {
                $this->data = [];
                $this->query = $get->displayName() . ' (#'.$get->id.')';
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

        $get = $query->get();

        if ($get != null) {
            $this->data = $get;
        }
    }
}
