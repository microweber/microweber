<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin\Traits;

trait WithOrdersBulkStatusModal
{
    public $statusModal = false;

    public function showStatusModal()
    {
        $this->statusModal = true;
    }

    public function hideStatusModal()
    {
        $this->statusModal = false;
    }

    public function statusExecute()
    {

    }
}
