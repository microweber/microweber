<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    @php
        $itemValue = '';
        $itemOperatorValue = '';
        if (isset($filters['sales'])) {
            $itemValue = $filters['sales'];
        }
        if (isset($filters['salesOperator'])) {
            $itemOperatorValue = $filters['salesOperator'];
        }
    @endphp

    @livewire('admin-filter-item-value-with-operator', [
        'name'=>'Sales',
        'itemValue'=>$itemValue,
        'itemOperatorValue'=>$itemOperatorValue,
        'itemValueKey'=>'sales',
        'itemOperatorValueKey'=>'salesOperator'
    ])

</div>
