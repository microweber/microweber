<?php

namespace MicroweberPackages\Marketplace\Http\Livewire\Admin;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use LivewireUI\Modal\ModalComponent;
use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\ComposerClient\Client;
use MicroweberPackages\Package\MicroweberComposerClient;
use MicroweberPackages\Package\MicroweberComposerPackage;

class MarketplaceItemModal extends AdminModalComponent
{
    public $name;
    public $package = [];
    public $installVersion = '';

    public $modalSettings = [
     //   'width'=>'800px',
        'overlay' => true,
        'overlayClose' => true,
    ];

    public function mount()
    {
        $foundedPackage = [];
        $foundedPackageVersions = [];
        $packageName = $this->name;
        $packages = Cache::remember('livewire-marketplace', Carbon::now()->addHours(12), function () {
            $marketplace = new MicroweberComposerClient();
            return $marketplace->search();
        });
        if (!empty($packages)) {
            foreach ($packages as $packageVersions) {
                foreach ($packageVersions as $packageVersion=>$packageVersionData) {
                    if ($packageVersionData['name'] == $packageName) {
                        $foundedPackage = $packageVersionData;
                        $foundedPackageVersions[] = $packageVersion;
                        $this->installVersion = $packageVersion;
                    }
                }
            }
        }

        usort($foundedPackageVersions, function($a, $b) {
            if ($a < $b) {
                return 1;
            } elseif ($a > $b) {
                return -1;
            }
            return 0;
        });

        $foundedPackage['versions'] = $foundedPackageVersions;
        $foundedPackage = MicroweberComposerPackage::format($foundedPackage);

        $this->package = $foundedPackage;
    }

    public function render()
    {
        return view('marketplace::admin.marketplace.livewire.modals.marketplace-item-modal');
    }
}
