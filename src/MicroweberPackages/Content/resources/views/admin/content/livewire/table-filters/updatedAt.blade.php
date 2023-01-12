<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $selectedItem = [];
        if (isset($filters['updatedAt'])) {
            $selectedItem = $filters['updatedAt'];
        }
    @endphp

    @livewire('admin-filter-item-date', [
        'name'=>'Updated At',
        'selectedItem'=>$selectedItem,
        'selectedItemKey'=>'updatedAt',
        'onChangedEmitEvents' => [
            'setFirstPageContentList'
        ]
    ])

</div>
