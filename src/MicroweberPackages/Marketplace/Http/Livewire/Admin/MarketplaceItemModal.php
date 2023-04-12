<?php

namespace MicroweberPackages\Marketplace\Http\Livewire\Admin;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use LivewireUI\Modal\ModalComponent;
use MicroweberPackages\ComposerClient\Client;
use MicroweberPackages\Package\MicroweberComposerClient;

class MarketplaceItemModal extends ModalComponent
{
    public $name;
    public $package = [];

    public function mount()
    {
        $name = $this->name;
        $package = Cache::remember('livewire-marketplace-' . $name, Carbon::now()->addHours(12), function () use($name) {
            $marketplace = new MicroweberComposerClient();
            return $marketplace->search([
                'require_name'=>$name
            ]);
        });

        $this->package = $package;
    }

    public function render()
    {
        return view('marketplace::admin.marketplace.livewire.modals.marketplace-item-modal');
    }
}
