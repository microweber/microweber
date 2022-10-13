<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $itemValue = '';
        if (isset($filters['id'])) {
            $itemValue = $filters['id'];
        }
    @endphp

    @livewire('admin-filter-item-value', [
        'name'=>'Order Id',
        'itemValue'=>$itemValue,
        'itemValueKey'=>'id',
        'showDropdown'=> session()->get('showFilterId'),
        'onChangedEmitEvents' => [
            'setFirstPageOrdersList'
        ]
    ])

</div>
