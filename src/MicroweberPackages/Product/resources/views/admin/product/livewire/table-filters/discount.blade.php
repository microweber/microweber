<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">
    @php
        $data = [
            ['key'=>'any','value'=>'Any'],
            ['key'=>'yes','value'=>'Discounted'],
            ['key'=>'no','value'=>'Not discounted'],
        ];

        $selectedItem = [];
        if (isset($filters['discounted'])) {
            $selectedItem = $filters['discounted'];
        }
    @endphp
    @livewire('admin-filter-item', [
        'name'=>'Discount',
        'searchable'=>false,
        'selectedItem'=>$selectedItem,
        'selectedItemKey'=>'discounted',
        'data'=>$data
    ])
</div>
