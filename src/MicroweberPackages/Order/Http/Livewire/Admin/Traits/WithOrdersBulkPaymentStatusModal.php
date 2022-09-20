<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin\Traits;

trait WithOrdersBulkPaymentStatusModal
{
    public $paymentStatusModal = false;

    public function showPaymentStatusModal()
    {
        $this->paymentStatusModal = true;
        $this->emit('paymentStatusModal', true);
    }

    public function hidePaymentStatusModal()
    {
        $this->paymentStatusModal = false;
        $this->emit('paymentStatusModal', false);
    }

    public function paymentStatusExecute($status = false)
    {
        $this->hidePaymentStatusModal();

        $this->emitSelf('$refresh');
    }
}
