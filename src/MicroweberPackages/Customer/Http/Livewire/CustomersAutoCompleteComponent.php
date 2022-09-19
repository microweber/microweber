<?php

namespace MicroweberPackages\Customer\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AutoCompleteComponent;
use MicroweberPackages\Customer\Models\Customer;

class CustomersAutoCompleteComponent extends AutoCompleteComponent
{
    public $model = Customer::class;
    public $selectedItemKey = 'customerId';
}
