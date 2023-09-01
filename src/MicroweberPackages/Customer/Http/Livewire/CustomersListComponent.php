<?php

namespace MicroweberPackages\Customer\Http\Livewire;

use MicroweberPackages\Content\Http\Livewire\Admin\ContentList;
use MicroweberPackages\Customer\Models\Customer;

class CustomersListComponent extends ContentList
{
    public $model = Customer::class;

    public $noActiveContentView = 'customer::admin.livewire.display-types.no-active-content';

    public $displayTypesViews = [
        'card'=>'customer::admin.livewire.display-types.card',
        'table'=>'customer::admin.livewire.display-types.table',
    ];

    public function getContentTypeProperty()
    {
        return 'Customer';
    }

    public function getDropdownFiltersProperty()
    {
        $dropdownFilters = [];
        $dropdownFilters[] = [
            'groupName' => 'Dates',
            'class'=> 'col-md-12',
            'filters'=> [
                [
                    'name' => 'Created at',
                    'key' => 'createdAt',
                ]
            ]
        ];

        return $dropdownFilters;
    }
}
