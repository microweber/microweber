<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin\Traits;

trait WithOrdersBulkOrderStatusModal
{
    public $orderStatusModal = false;

    public function showOrderStatusModal()
    {
        $this->orderStatusModal = true;
    }

    public function hideOrderStatusModal()
    {
        $this->orderStatusModal = false;
    }

    public function orderStatusExecute()
    {

    }
}
