<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin\Traits;

trait WithOrdersBulkStatusModal
{
    public $statusModal = false;

    public function showStatusModal()
    {
        $this->statusModal = true;
        $this->emit('statusModal', true);
    }

    public function hideStatusModal()
    {
        $this->statusModal = false;
        $this->emit('statusModal', false);
    }

    public function statusExecute()
    {
        $this->hideStatusModal();

        $this->emitSelf('$refresh');
    }
}
