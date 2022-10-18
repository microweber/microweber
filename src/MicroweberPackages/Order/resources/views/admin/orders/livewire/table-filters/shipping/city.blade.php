<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $itemValue = '';
        if (isset($filters['shippingCity'])) {
            $itemValue = $filters['shippingCity'];
        }
    @endphp

    @livewire('admin-filter-item-value', [
        'name'=>'Shipping City',
        'itemValue'=>$itemValue,
        'itemValueKey'=>'shippingCity',
        'showDropdown'=> session()->get('showFilterShippingCity'),
        'onChangedEmitEvents' => [
             'setFirstPageOrdersList'
        ]
    ])

</div>
