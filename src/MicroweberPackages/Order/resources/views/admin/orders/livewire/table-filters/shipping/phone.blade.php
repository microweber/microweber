<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $itemValue = '';
        if (isset($filters['shippingPhone'])) {
            $itemValue = $filters['shippingPhone'];
        }
    @endphp

    @livewire('admin-filter-item-value', [
        'name'=>'Shipping Phone',
        'itemValue'=>$itemValue,
        'itemValueKey'=>'shippingPhone',
        'showDropdown'=> session()->get('showFilterShippingPhone'),
        'onChangedEmitEvents' => [
            'setFirstPageOrdersList'
        ]
    ])

</div>
