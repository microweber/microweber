<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin\Traits;

trait WithOrdersBulkPaymentStatusModal
{
    public $bulkPaymentStatus = '';
    public $paymentStatusModal = false;

    public function showPaymentStatusModal()
    {
        $this->paymentStatusModal = true;
    }

    public function hidePaymentStatusModal()
    {
        $this->paymentStatusModal = false;
    }

    public function paymentStatusExecute()
    {

    }
}
