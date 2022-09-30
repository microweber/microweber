<div class="ms-0 ms-md-2 mb-3 mb-md-0">
    @php
        $data = [
            ['key'=>'','value'=>'Any'],
            ['key'=>'1','value'=>'In Stock'],
            ['key'=>'0','value'=>'Out Of Stock'],
        ];

        $selectedItem = [];
        if (isset($filters['inStock'])) {
            $selectedItem = $filters['inStock'];
        }
    @endphp
    @livewire('admin-filter-item', [
        'name'=>'Stock Status',
        'selectedItem'=>$selectedItem,
        'selectedItemKey'=>'inStock',
        'data'=>$data
    ])
</div>
