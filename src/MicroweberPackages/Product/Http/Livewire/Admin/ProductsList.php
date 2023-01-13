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

        $dropdownFilters[] = [
            'name' => 'Taxonomy',
            'type' => 'separator'
        ];

        $dropdownFilters[] = [
            'name' => 'Tags',
            'key' => 'tags'
        ];

        $dropdownFilters[] = [
            'name' => 'Shop',
            'type' => 'separator'
        ];

        $dropdownFilters[] = [
            'name' => 'Price Range',
            'key' => 'priceBetween',
            'viewNamespace'=> 'product::admin.product.livewire.table-filters.price-between'
        ];

        $dropdownFilters[] = [
            'name' => 'Stock Status',
            'key' => 'stockStatus',
            'viewNamespace'=> 'product::admin.product.livewire.table-filters.stock-status'
        ];

        $dropdownFilters[] = [
            'name' => 'Discount',
            'key' => 'discount',
            'viewNamespace'=> 'product::admin.product.livewire.table-filters.discount'
        ];

        $dropdownFilters[] = [
            'name' => 'Orders',
            'key' => 'orders',
            'viewNamespace'=> 'product::admin.product.livewire.table-filters.orders'
        ];

        $dropdownFilters[] = [
            'name' => 'Quantity',
            'key' => 'qty',
            'viewNamespace'=> 'product::admin.product.livewire.table-filters.quantity'
        ];

        $dropdownFilters[] = [
            'name' => 'Sku',
            'key' => 'sku',
            'viewNamespace'=> 'product::admin.product.livewire.table-filters.sku'
        ];

        $templateFields = $this->getDropdownFiltersTemplateSettings();
        $dropdownFilters = array_merge($dropdownFilters, $templateFields);

        $templateFields = $this->getDropdownFiltersTemplateFields();
        $dropdownFilters = array_merge($dropdownFilters, $templateFields);

        $dropdownFilters[] = [
            'name' => 'Other',
            'type' => 'separator'
        ];

        $dropdownFilters[] = [
            'name' => 'Visible',
            'key' => 'visible',
        ];
        $dropdownFilters[] = [
            'name' => 'Author',
            'key' => 'userId',
        ];

        $dropdownFilters[] = [
            'name' => 'Date Range',
            'key' => 'dateBetween',
        ];

        $dropdownFilters[] = [
            'name' => 'Created at',
            'key' => 'createdAt',
        ];

        $dropdownFilters[] = [
            'name' => 'Updated at',
            'key' => 'updatedAt',
        ];

        return $dropdownFilters;
    }

}

