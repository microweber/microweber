<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $itemValue = '';
        if (isset($filters['shippingZip'])) {
            $itemValue = $filters['shippingZip'];
        }
    @endphp

    @livewire('admin-filter-item-value', [
        'name'=>'Shipping Post Code',
        'itemValue'=>$itemValue,
        'itemValueKey'=>'shippingZip',
        'showDropdown'=> session()->get('showFilterShippingZip'),
        'onChangedEmitEvents' => [
         'setFirstPageOrdersList'
        ]
    ])

</div>
