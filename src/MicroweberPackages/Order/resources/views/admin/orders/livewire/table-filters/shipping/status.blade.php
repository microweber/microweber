<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">
    @php
        $data = [
            ['key'=>'any','value'=>'Any'],
            ['key'=>'received','value'=>'Received'],
            ['key'=>'pending','value'=>'Pending'],
            ['key'=>'shipped','value'=>'Shipped'],
        ];

        $selectedItem = [];
        if (isset($filters['shippingStatus'])) {
            $selectedItem = $filters['shippingStatus'];
        }
    @endphp
    @livewire('admin-filter-item', [
        'name'=>'Shipping Status',
        'searchable'=>false,
        'selectedItem'=>$selectedItem,
        'selectedItemKey'=>'shippingStatus',
        'data'=>$data,
        'showDropdown'=> session()->get('showFilterShippingStatus'),
        'onChangedEmitEvents' => [
            'setFirstPageOrdersList'
        ]
    ])
</div>
