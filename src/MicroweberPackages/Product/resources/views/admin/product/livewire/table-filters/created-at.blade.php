<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $selectedItem = [];
        if (isset($filters['createdAt'])) {
            $selectedItem = $filters['createdAt'];
        }
    @endphp

    @livewire('admin-filter-item-date', [
        'name'=>'Created At',
        'selectedItem'=>$selectedItem,
        'selectedItemKey'=>'createdAt',
        'showDropdown'=> session()->get('showFilterDate'),
        'onChangedEmitEvents' => [
         'setFirstPageProductsList'
        ]
    ])

</div>
