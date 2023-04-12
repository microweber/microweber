<?php

namespace MicroweberPackages\Marketplace\Http\Livewire\Admin;

use LivewireUI\Modal\ModalComponent;

class MarketplaceItemModal extends ModalComponent
{

    public $name;

    public function render()
    {
        return view('marketplace::admin.marketplace.livewire.modals.marketplace-item-modal');
    }
}
