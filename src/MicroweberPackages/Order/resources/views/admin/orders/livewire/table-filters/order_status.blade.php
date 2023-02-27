<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">
    @php
        $data = [
            ['key'=>'any','value'=>'Any'],
            ['key'=>'new','value'=>'New'],
            ['key'=>'pending','value'=>'Pending'],
            ['key'=>'completed','value'=>'Completed'],
        ];

        $selectedItem = [];
        if (isset($filters['orderStatus'])) {
            $selectedItem = $filters['orderStatus'];
        }
    @endphp
    @livewire('admin-filter-item', [
        'name'=>'Order Status',
        'searchable'=>false,
        'selectedItem'=>$selectedItem,
        'selectedItemKey'=>'orderStatus',
        'data'=>$data,
        'showDropdown'=> session()->get('showFilterOrderStatus'),
        'onChangedEmitEvents' => [
            'setFirstPageOrdersList'
        ]
    ])
</div>
