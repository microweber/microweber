<?php

namespace MicroweberPackages\Admin\tests;



use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\Admin\Http\Livewire\AutoCompleteComponent;
use MicroweberPackages\Admin\Http\Livewire\AutoCompleteMultipleSelectComponent;
use MicroweberPackages\Admin\Http\Livewire\FilterItemCateogry;
use MicroweberPackages\Admin\Http\Livewire\FilterItemComponent;
use MicroweberPackages\Admin\Http\Livewire\FilterItemDate;
use MicroweberPackages\Admin\Http\Livewire\FilterItemDateRange;
use MicroweberPackages\Admin\Http\Livewire\FilterItemMultipleSelectComponent;
use MicroweberPackages\Admin\Http\Livewire\FilterItemProduct;
use MicroweberPackages\Admin\Http\Livewire\FilterItemTags;
use MicroweberPackages\Admin\Http\Livewire\FilterItemUser;
use MicroweberPackages\Admin\Http\Livewire\FilterItemValue;
use MicroweberPackages\Admin\Http\Livewire\FilterItemValueRange;
use MicroweberPackages\Admin\Http\Livewire\FilterItemValueWithOperator;
use MicroweberPackages\Admin\Http\Livewire\TagsAutoComplete;
use MicroweberPackages\Admin\Http\Livewire\UsersAutoComplete;
use MicroweberPackages\User\tests\UserLivewireComponentsAccessTest;

class AdminLivewireComponentsTest extends UserLivewireComponentsAccessTest
{
    public $componentsList = [
        AutoCompleteComponent::class,
        AutoCompleteMultipleSelectComponent::class,
        FilterItemCateogry::class,
        FilterItemComponent::class,
        FilterItemDate::class,
        FilterItemDateRange::class,
        FilterItemMultipleSelectComponent::class,
        FilterItemProduct::class,
        FilterItemTags::class,
        FilterItemUser::class,
        FilterItemValue::class,
        FilterItemValueRange::class,
        FilterItemValueWithOperator::class,
        TagsAutoComplete::class,
        UsersAutoComplete::class,
    ];
 }
