<div class="ms-0 ms-md-2 mb-3 mb-md-0">
    @php
        $data = [
            ['key'=>'','value'=>'Any'],
            ['key'=>'1','value'=>'Discounted'],
            ['key'=>'0','value'=>'Not discounted'],
        ];

        $selectedItem = [];
        if (isset($filters['discounted'])) {
            $selectedItem = $filters['discounted'];
        }
    @endphp
    @livewire('admin-filter-item', [
        'name'=>'Discount',
        'selectedItem'=>$selectedItem,
        'selectedItemKey'=>'discounted',
        'data'=>$data
    ])
</div>
