<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $itemValue = '';
        if (isset($filters['contentFields'])) {
            $itemValue = $filters['contentFields'];
        }
    @endphp

    @livewire('admin-filter-item-value', [
        'name'=>'Content Fields',
        'itemValue'=>$itemValue,
        'itemValueKey'=>'ContentFields',
        'showDropdown'=> session()->get('showFilterContentFields'),
        'onChangedEmitEvents' => [
             'setFirstPageContentList'
        ]
    ])

</div>
