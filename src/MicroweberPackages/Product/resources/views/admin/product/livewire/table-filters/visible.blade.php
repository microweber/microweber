<div class="ms-0 ms-md-2 mb-3 mb-md-0">

    @php
        $data = [
            ['key'=>'','value'=>'Any'],
            ['key'=>'1','value'=>'Published'],
            ['key'=>'0','value'=>'Unpublished'],
        ];

        $selectedItem = [];
        if (isset($filters['visible'])) {
            $selectedItem = $filters['visible'];
        }
    @endphp

    @livewire('admin-filter-item', [
        'name'=>'Visible',
        'selectedItem'=>$selectedItem,
        'selectedItemKey'=>'visible',
        'data'=>$data
    ])

</div>
