<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin\Modals;

use LivewireUI\Modal\ModalComponent;

class OrdersBulkPaymentStatus extends ModalComponent
{
    public function render()
    {
        return view('order::admin.orders.livewire.bulk-modals.payment-status');
    }
}
