<?php

namespace MicroweberPackages\Customer\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AutoCompleteComponent;
use Modules\Customer\Models\Customer;

/* @deprecated  */
class CustomersAutoCompleteComponent extends AutoCompleteComponent
{
    public $model = Customer::class;
    public $selectedItemKey = 'customerId';
}
