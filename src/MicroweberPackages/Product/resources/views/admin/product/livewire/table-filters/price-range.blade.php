<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $itemValue = '';
        if (isset($filters['priceRange'])) {
            $itemValue = $filters['priceRange'];
        }
    @endphp

    @livewire('admin-filter-item-value-range', [
        'name'=>'Price Range',
        'itemValue'=>$itemValue,
        'itemValueKey'=>'priceBetween',
        'showDropdown'=> session()->get('showFilterPriceRange'),
        'onChangedEmitEvents' => [
            'setFirstPageProductsList'
        ]
    ])

</div>
