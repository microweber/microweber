<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $itemValue = '';
        $itemOperatorValue = '';
        if (isset($filters['orders'])) {
            $itemValue = $filters['orders'];
        }
        if (isset($filters['ordersOperator'])) {
            $itemOperatorValue = $filters['ordersOperator'];
        }
    @endphp

    @livewire('admin-filter-item-value-with-operator', [
        'name'=>'Orders',
        'itemValue'=>$itemValue,
        'itemOperatorValue'=>$itemOperatorValue,
        'itemValueKey'=>'orders',
        'itemOperatorValueKey'=>'ordersOperator',
        'showDropdown'=> session()->get('showFilterOrders'),
        'onChangedEmitEvents' => [
          'setFirstPageContentList'
        ]
    ])

</div>
