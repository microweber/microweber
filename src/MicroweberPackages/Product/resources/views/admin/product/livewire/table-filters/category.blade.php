<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $selectedItems = [];
        if (isset($filters['category'])) {
            $selectedItems = explode(',', $filters['category']);
        }
    @endphp
    @livewire('admin-filter-item-category', [
        // 'selectedItems'=>$selectedItems,
        'showDropdown'=> session()->get('showFilterCategory'),
        'onChangedEmitEvents' => [
            'setFirstPageProductsList'
        ]
    ])

</div>
