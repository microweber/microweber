<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin\Traits;

trait WithOrdersBulkOrderStatusModal
{
    public $orderStatusModal = false;

    public function showorderStatusModal()
    {
        $this->orderStatusModal = true;
    }

    public function hideorderStatusModal()
    {
        $this->orderStatusModal = false;
    }

    public function orderStatusExecute()
    {

    }
}
