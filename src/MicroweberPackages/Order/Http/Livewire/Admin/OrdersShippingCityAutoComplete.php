<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin;


class OrdersShippingCityAutoComplete extends OrdersShippingCountryAutoComplete
{
    public string $placeholder = 'Type to search by city...';
    public $modelGroupByField = 'city';
}
