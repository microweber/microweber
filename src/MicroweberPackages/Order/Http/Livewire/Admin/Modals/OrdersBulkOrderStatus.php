<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin\Modals;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use Modules\Order\Models\Order;

class OrdersBulkOrderStatus extends AdminModalComponent
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

        $this->dispatch('refreshOrdersFilters');
        $this->closeModal();
    }

    public function mount()
    {
        $this->orderStatuses = \Modules\Order\Models\Order::getOrderStatuses();
    }

    public function render()
    {
        return view('order::admin.orders.livewire.bulk-modals.order-status');
    }
}
