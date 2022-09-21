<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin;


class OrdersShippingCityAutoComplete extends OrdersShippingCountryAutoComplete
{
    public $selectedItemKey = 'shipping.city';
    public string $placeholder = 'Type to search city...';
    public $modelGroupByField = 'city';
}
