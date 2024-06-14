<?php

namespace MicroweberPackages\Product\tests;


use MicroweberPackages\Product\Http\Livewire\Admin\ProductsList;
use MicroweberPackages\Notification\tests\UserLivewireComponentsAccessTest;

class ProductLivewireComponentsAccessTest extends UserLivewireComponentsAccessTest
{
    public $componentsList = [
        ProductsList::class,
    ];
}
