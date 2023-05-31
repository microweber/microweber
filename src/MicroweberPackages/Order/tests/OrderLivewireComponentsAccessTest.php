<?php

namespace MicroweberPackages\Order\tests;


use MicroweberPackages\Order\Http\Livewire\Admin\FilterItemOrderCustomer;
use MicroweberPackages\Order\Http\Livewire\Admin\FilterItemOrdersShippingAddress;
use MicroweberPackages\Order\Http\Livewire\Admin\Modals\OrdersBulkDelete;
use MicroweberPackages\Order\Http\Livewire\Admin\Modals\OrdersBulkOrderStatus;
use MicroweberPackages\Order\Http\Livewire\Admin\Modals\OrdersBulkPaymentStatus;
use MicroweberPackages\Order\Http\Livewire\Admin\OrdersFiltersComponent;
use MicroweberPackages\Order\Http\Livewire\Admin\OrdersShippingCityAutoComplete;
use MicroweberPackages\Order\Http\Livewire\Admin\OrdersShippingCountryAutoComplete;
use MicroweberPackages\Order\Http\Livewire\Admin\OrdersShippingStateAutoComplete;
use MicroweberPackages\Order\Http\Livewire\Admin\OrdersTableComponent;
use MicroweberPackages\User\tests\UserLivewireComponentsAccessTest;

class OrderLivewireComponentsAccessTest extends UserLivewireComponentsAccessTest
{
    public $componentsList = [
        OrdersTableComponent::class,
        OrdersShippingStateAutoComplete::class,
        OrdersShippingCountryAutoComplete::class,
        OrdersShippingCityAutoComplete::class,
        OrdersFiltersComponent::class,
        FilterItemOrdersShippingAddress::class,
        FilterItemOrderCustomer::class,
        OrdersBulkPaymentStatus::class,
        OrdersBulkOrderStatus::class,
        OrdersBulkDelete::class,
     ];
}
