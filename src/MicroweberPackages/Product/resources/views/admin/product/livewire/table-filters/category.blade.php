<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $itemCategoryValue = '';
        if (isset($filters['category'])) {
            $itemCategoryValue = $filters['category'];
        }
        $itemPageValue = '';
        if (isset($filters['page'])) {
            $itemPageValue = $filters['page'];
        }
    @endphp
    @livewire('admin-filter-item-category', [
        'itemCategoryValue'=>$itemCategoryValue,
        'itemPageValue'=>$itemPageValue,
        'showDropdown'=> session()->get('showFilterCategory'),
        'onChangedEmitEvents' => [
            'setFirstPageProductsList'
        ]
    ])

</div>
