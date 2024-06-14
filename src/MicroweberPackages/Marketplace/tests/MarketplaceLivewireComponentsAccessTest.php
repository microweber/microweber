<?php

namespace MicroweberPackages\Marketplace\tests;

use MicroweberPackages\Marketplace\Http\Livewire\Admin\Marketplace;
use MicroweberPackages\Marketplace\Http\Livewire\Admin\MarketplaceItemModal;
use MicroweberPackages\Notification\tests\UserLivewireComponentsAccessTest;

class MarketplaceLivewireComponentsAccessTest extends UserLivewireComponentsAccessTest
{
    public $componentsList = [
        Marketplace::class,
        MarketplaceItemModal::class,
    ];
}
