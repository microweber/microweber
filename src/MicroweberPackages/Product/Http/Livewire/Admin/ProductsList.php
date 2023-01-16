<?php

namespace MicroweberPackages\Product\Http\Livewire\Admin;

use MicroweberPackages\Content\Http\Livewire\Admin\ContentList;
use MicroweberPackages\Product\Models\Product;

class ProductsList extends ContentList
{
    public $model = Product::class;

    public $whitelistedEmptyKeys = ['inStock', 'orders', 'qty'];

    public $showColumns = [
        'id' => true,
        'image' => true,
        'title' => true,
        'price' => true,
        'stock' => true,
        'orders' => true,
        'quantity' => true,
        'author' => false
    ];

    public function getDropdownFiltersProperty()
    {
        $dropdownFilters = [];

        $dateFields = $this->getDropdownFiltersTaxonomies();
        $dropdownFilters = array_merge($dropdownFilters, $dateFields);

        $dropdownFilters[] = [
            'groupName' => 'Shop',
            'class'=> 'col-md-6',
            'filters'=> [
                [
                    'name' => 'Price Range',
                    'key' => 'priceBetween',
                    'viewNamespace'=> 'product::admin.product.livewire.table-filters.price-between'
                ],
                [
                    'name' => 'Stock Status',
                    'key' => 'stockStatus',
                    'viewNamespace'=> 'product::admin.product.livewire.table-filters.stock-status'
                ],
                [
                    'name' => 'Discount',
                    'key' => 'discount',
                    'viewNamespace'=> 'product::admin.product.livewire.table-filters.discount'
                ],
                [
                    'name' => 'Orders',
                    'key' => 'orders',
                    'viewNamespace'=> 'product::admin.product.livewire.table-filters.orders'
                ],
                [
                    'name' => 'Quantity',
                    'key' => 'qty',
                    'viewNamespace'=> 'product::admin.product.livewire.table-filters.quantity'
                ],
                [
                    'name' => 'Sku',
                    'key' => 'sku',
                    'viewNamespace'=> 'product::admin.product.livewire.table-filters.sku'
                ]
            ]
        ];

        $templateFields = $this->getDropdownFiltersTemplateSettings();
        $dropdownFilters = array_merge($dropdownFilters, $templateFields);

        $templateFields = $this->getDropdownFiltersTemplateFields();
        $dropdownFilters = array_merge($dropdownFilters, $templateFields);

        $otherFields = $this->getDropdownFiltersOthers();
        $dropdownFilters = array_merge($dropdownFilters, $otherFields);


        $dateFields = $this->getDropdownFiltersDates();
        $dropdownFilters = array_merge($dropdownFilters, $dateFields);


        return $dropdownFilters;
    }

}

