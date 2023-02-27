<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $itemValue = '';
        if (isset($filters['shippingAddress'])) {
            $itemValue = $filters['shippingAddress'];
        }
    @endphp

    @livewire('admin-filter-item-value', [
    'name'=>'Shipping Address',
    'itemValue'=>$itemValue,
    'itemValueKey'=>'shippingAddress',
    'showDropdown'=> session()->get('showFilterId'),
    'onChangedEmitEvents' => [
    'setFirstPageOrdersList'
    ]
    ])

</div>
