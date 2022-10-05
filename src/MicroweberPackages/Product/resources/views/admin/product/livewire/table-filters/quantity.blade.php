<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $itemValue = '';
        $itemOperatorValue = '';
        if (isset($filters['qty'])) {
            $itemValue = $filters['qty'];
        }
        if (isset($filters['qtyOperator'])) {
            $itemOperatorValue = $filters['qtyOperator'];
        }
    @endphp

    @livewire('admin-filter-item-value-with-operator', [
        'name'=>'Quantity',
        'itemValue'=>$itemValue,
        'itemOperatorValue'=>$itemOperatorValue,
        'itemValueKey'=>'qty',
        'itemOperatorValueKey'=>'qtyOperator',
        'showDropdown'=> session()->get('showFilterQty'),
        'onChangedEmitEvents' => [
            'setFirstPageProductsList'
        ]
    ])

</div>
