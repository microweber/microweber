<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $itemValue = '';
        if (isset($filters['contentData'][$fieldKey])) {
            $itemValue = $filters['contentData'][$fieldKey];
        }
    @endphp

    @livewire('admin-filter-item-value', [
        'name'=> $fieldName,
        'itemValue'=>$itemValue,
        'itemValueKey'=>['contentData'=>$fieldKey],
        'showDropdown'=> session()->get('showFilterContentFields'),
        'onChangedEmitEvents' => [
             'setFirstPageContentList'
        ]
    ])

</div>
