<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $itemValue = '';
        if (isset($filters['dateBetween'])) {
            $itemValue = $filters['dateBetween'];
        }
    @endphp

    @livewire('admin-filter-item-date-range', [
        'name'=>'Date Between',
        'itemValue'=>$itemValue,
        'itemValueKey'=>'dateBetween',
        'showDropdown'=> session()->get('showFilterDateBetween'),
        'onChangedEmitEvents' => [
            'setFirstPageContentList'
        ]
    ])

</div>
