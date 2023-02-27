<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $itemValue = '';
        if (isset($filters['shippingCountry'])) {
            $itemValue = $filters['shippingCountry'];
        }
    @endphp

    @livewire('admin-filter-item-value', [
        'name'=>'Shipping Country',
        'itemValue'=>$itemValue,
        'itemValueKey'=>'shippingCountry',
        'showDropdown'=> session()->get('showFilterShippingCountry'),
        'onChangedEmitEvents' => [
            'setFirstPageOrdersList'
        ]
    ])

</div>
