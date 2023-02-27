<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">
    @php
        $data = [
            ['key'=>'any','value'=>'Any'],
            ['key'=>'yes','value'=>'Discounted'],
            ['key'=>'no','value'=>'Not discounted'],
        ];

        $selectedItem = [];
        if (isset($filters['discount'])) {
            $selectedItem = $filters['discount'];
        }
    @endphp
    @livewire('admin-filter-item', [
        'name'=>'Discount',
        'searchable'=>false,
        'selectedItem'=>$selectedItem,
        'selectedItemKey'=>'discount',
        'data'=>$data,
        'showDropdown'=> session()->get('showFilterDiscount'),
        'onChangedEmitEvents' => [
            'setFirstPageContentList'
        ]
    ])
</div>
