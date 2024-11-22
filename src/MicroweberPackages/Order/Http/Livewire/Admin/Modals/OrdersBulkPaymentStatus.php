<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin\Modals;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use Modules\Order\Models\Order;

class OrdersBulkPaymentStatus extends AdminModalComponent
{
    public $paymentStatus;
    public $ids = [];

    public function change()
    {
        $this->paymentStatus = intval($this->paymentStatus);

        Order::whereIn('id', $this->ids)->update(['is_paid'=>$this->paymentStatus]);

        $this->dispatch('refreshOrdersFilters');
        $this->closeModal();
    }

    public function render()
    {
        return view('order::admin.orders.livewire.bulk-modals.payment-status');
    }
}
