<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin\Traits;

trait WithOrdersBulkDeleteModal
{
    public $deleteModal = false;

    public function showDeleteModal()
    {
        $this->deleteModal = true;
    }

    public function hideDeleteModal()
    {
        $this->deleteModal = false;
    }

    public function deleteExecute()
    {

    }
}
