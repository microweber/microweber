<?php

namespace MicroweberPackages\Marketplace\Http\Livewire\Admin;

use Livewire\Component;
use MicroweberPackages\Package\MicroweberComposerClient;

class Marketplace extends Component
{
    public $marketplace = [];
    public $category = 'microweber-template';

    public function filterCategory($category)
    {
        $this->category = $category;
        $this->filter();
    }

    public function mount()
    {
        $this->filter();
    }

    public function filter()
    {
        $marketplace = new MicroweberComposerClient();
        $packages = $marketplace->search();
        $latestVersions = [];
        foreach ($packages as $packageName=>$package) {
            $latestVersionPackage = end($package);

            if (!empty($this->category)) {
                if ($latestVersionPackage['type'] != $this->category) {
                    continue;
                }
            }

            $latestVersions[$packageName] = $latestVersionPackage;
        }

        $this->marketplace = $latestVersions;
    }

    public function render()
    {
        return view('marketplace::admin.marketplace.livewire.index');
    }
}
