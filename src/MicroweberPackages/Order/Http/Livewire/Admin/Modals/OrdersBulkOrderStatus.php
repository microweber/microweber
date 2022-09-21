<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin\Modals;

use LivewireUI\Modal\ModalComponent;
use MicroweberPackages\Order\Models\Order;

class OrdersBulkOrderStatus extends ModalComponent
{
    public $orderStatuses = [];
    public $orderStatus;
    public $ids = [];

    public function change()
    {
        if (!isset($this->orderStatuses[$this->orderStatus])) {
            return;
        }

        Order::whereIn('id', $this->ids)->update(['order_status'=>$this->orderStatus]);

        $this->emit('refreshOrdersFilters');
        $this->closeModal();
    }

    public function mount()
    {
        $this->orderStatuses = \MicroweberPackages\Order\Models\Order::getOrderStatuses();
    }

    public function render()
    {
        return view('order::admin.orders.livewire.bulk-modals.order-status');
    }
}
