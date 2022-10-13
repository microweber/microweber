<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $itemValue = '';
        if (isset($filters['amountBetween'])) {
            $itemValue = $filters['amountBetween'];
        }
    @endphp

    @livewire('admin-filter-item-value-range', [
        'name'=>'Order Amount Range',
        'itemValue'=>$itemValue,
        'itemValueKey'=>'amountBetween',
        'showDropdown'=> session()->get('showFilterAmountBetween'),
        'onChangedEmitEvents' => [
            'setFirstPageOrdersList'
        ]
    ])

</div>
