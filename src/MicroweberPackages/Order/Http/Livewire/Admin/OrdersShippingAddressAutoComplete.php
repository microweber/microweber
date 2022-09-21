<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin;

class OrdersShippingAddressAutoComplete extends OrdersShippingCountryAutoComplete
{
    public $selectedItemKey = 'shipping.address';
    public string $placeholder = 'Type to search address...';

}
