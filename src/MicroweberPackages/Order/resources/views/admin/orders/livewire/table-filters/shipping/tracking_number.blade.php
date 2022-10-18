<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $itemValue = '';
        if (isset($filters['shippingTrackingNumber'])) {
            $itemValue = $filters['shippingTrackingNumber'];
        }
    @endphp

    @livewire('admin-filter-item-value', [
        'name'=>'Shipping Tracking Number',
        'itemValue'=>$itemValue,
        'itemValueKey'=>'shippingTrackingNumber',
        'showDropdown'=> session()->get('showFilterShippingTrackingNumber'),
        'onChangedEmitEvents' => [
            'setFirstPageOrdersList'
        ]
    ])

</div>
