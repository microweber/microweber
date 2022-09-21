<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin\Modals;

use LivewireUI\Modal\ModalComponent;
use MicroweberPackages\Order\Models\Order;

class OrdersBulkDelete extends ModalComponent
{
    public $ids = [];

    public function delete()
    {
        Order::whereIn('id', $this->ids)->delete();
        $this->emit('refreshOrdersFilters');
        $this->closeModal();
    }

    public function render()
    {
        return view('order::admin.orders.livewire.bulk-modals.delete');
    }
}
