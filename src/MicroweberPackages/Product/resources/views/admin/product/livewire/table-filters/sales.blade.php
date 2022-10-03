<div class="ms-0 ms-md-2 mb-3 mb-md-0">


    @php
        $data = [];

        $selectedItem = [];
        if (isset($filters['sales'])) {
            $selectedItem = $filters['sales'];
        }
    @endphp

    @livewire('admin-filter-item-value-with-operator', [
        'name'=>'Sales',
        'selectedItem'=>$selectedItem,
        'selectedItemKey'=>'sales',
        'data'=>$data
    ])

</div>
