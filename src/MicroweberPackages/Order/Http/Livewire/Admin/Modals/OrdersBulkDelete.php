<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin\Modals;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use Modules\Order\Models\Order;

class OrdersBulkDelete extends AdminModalComponent
{
    public $ids = [];

    public function delete()
    {
        Order::whereIn('id', $this->ids)->delete();
        $this->dispatch('refreshOrdersFilters');
        $this->closeModal();
    }

    public function render()
    {
        return view('order::admin.orders.livewire.bulk-modals.delete');
    }
}
