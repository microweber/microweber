<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin\Modals;

use LivewireUI\Modal\ModalComponent;
use MicroweberPackages\Order\Models\Order;

class OrdersBulkPaymentStatus extends ModalComponent
{
    public $paymentStatus;
    public $ids = [];

    public function change()
    {
        $this->paymentStatus = intval($this->paymentStatus);

        Order::whereIn('id', $this->ids)->update(['is_paid'=>$this->paymentStatus]);

        $this->emit('refreshOrdersFilters');
        $this->closeModal();
    }

    public function render()
    {
        return view('order::admin.orders.livewire.bulk-modals.payment-status');
    }
}
