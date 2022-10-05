<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $selectedItems = [];
        if (isset($filters['tags'])) {
            $selectedItems = explode(',', $filters['tags']);
        }
    @endphp
    @livewire('admin-filter-item-tags', [
        'selectedItems'=>$selectedItems,
        'showDropdown'=> session()->get('showFilterTags'),
        'onChangedEmitEvents' => [
            'setFirstPageProductsList'
        ]
    ])

</div>
