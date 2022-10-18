<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">
    @php
        $data = [
            ['key'=>'any','value'=>'Any'],
            ['key'=>'1','value'=>'Paid'],
            ['key'=>'0','value'=>'Unpaid']
        ];

        $selectedItem = [];
        if (isset($filters['isPaid'])) {
            $selectedItem = $filters['isPaid'];
        }
    @endphp
    @livewire('admin-filter-item', [
        'name'=>'Payment Status',
        'searchable'=>false,
        'selectedItem'=>$selectedItem,
        'selectedItemKey'=>'isPaid',
        'data'=>$data,
        'showDropdown'=> session()->get('showFilterIsPaid'),
        'onChangedEmitEvents' => [
         'setFirstPageOrdersList'
        ]
    ])
</div>
