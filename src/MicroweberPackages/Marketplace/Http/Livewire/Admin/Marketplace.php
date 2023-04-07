<?php

namespace MicroweberPackages\Marketplace\Http\Livewire\Admin;

use Livewire\Component;
use MicroweberPackages\Package\MicroweberComposerClient;

class Marketplace extends Component
{
    public $marketplace;

    public function mount()
    {
        $marketplace = new MicroweberComposerClient();
        $packages = $marketplace->search();
        $latestVersions = [];
        foreach ($packages as $packageName=>$package) {
            $latestVersions[$packageName] = end($package);
            $latestVersions[$packageName]['versions'] = $package;
        }

        $this->marketplace = $latestVersions;
    }

    public function render()
    {
        return view('marketplace::admin.marketplace.livewire.index');
    }
}
