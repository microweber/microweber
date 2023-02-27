<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $itemValue = '';
        if (isset($filters['priceBetween'])) {
            $itemValue = $filters['priceBetween'];
        }
    @endphp

    @livewire('admin-filter-item-value-range', [
        'name'=>'Price Between',
        'itemValue'=>$itemValue,
        'itemValueKey'=>'priceBetween',
        'showDropdown'=> session()->get('showFilterPriceBetween'),
        'onChangedEmitEvents' => [
            'setFirstPageContentList'
        ]
    ])

</div>
