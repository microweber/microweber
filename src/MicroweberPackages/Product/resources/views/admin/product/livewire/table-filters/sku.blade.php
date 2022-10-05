<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $itemValue = '';
        if (isset($filters['sku'])) {
            $itemValue = $filters['sku'];
        }
    @endphp

    @livewire('admin-filter-item-value', [
        'name'=>'SKU',
        'itemValue'=>$itemValue,
        'itemValueKey'=>'sku',
        'onChangedEmitEvents' => [
            'setFirstPageProductsList'
        ]
    ])

</div>
