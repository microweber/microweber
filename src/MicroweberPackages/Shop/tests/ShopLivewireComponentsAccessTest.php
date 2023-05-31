<?php

namespace MicroweberPackages\Shop\tests;


use MicroweberPackages\Shop\Http\Livewire\Admin\DashboardSalesComponent;
use MicroweberPackages\User\tests\UserLivewireComponentsAccessTest;

class ShopLivewireComponentsAccessTest extends UserLivewireComponentsAccessTest
{
    public $componentsList = [
        DashboardSalesComponent::class,
    ];
}
