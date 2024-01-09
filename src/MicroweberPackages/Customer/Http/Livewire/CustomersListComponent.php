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

    public function getCardsStats()
    {
        return [
          [
                'name' => 'Customers',
                'value' => $this->contentsQuery->count(),
                'icon' => 'mdi mdi-account-multiple',
                'bgClass' => 'bg-primary',
                'textClass' => 'text-white'
          ]
        ];
    }

    public function getDropdownFiltersProperty()
    {
        $dropdownFilters = [];

        $datesFields = $this->getDropdownFiltersDates();
        $dropdownFilters = array_merge($dropdownFilters, $datesFields);

        return $dropdownFilters;
    }
}
