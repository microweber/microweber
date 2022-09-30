<div class="ms-0 ms-md-2 mb-3 mb-md-0">

    @php
        $selectedItem = [];
        if (isset($filters['createdAt'])) {
            $selectedItem = $filters['createdAt'];
        }
    @endphp

    @livewire('admin-filter-item-date', [
        'name'=>'Created At',
        'selectedItem'=>$selectedItem,
        'selectedItemKey'=>'createdAt'
    ])

</div>

<div class="ms-0 ms-md-2 mb-3 mb-md-0">

    @php
        $selectedItem = [];
        if (isset($filters['updatedAt'])) {
            $selectedItem = $filters['updatedAt'];
        }
    @endphp

    @livewire('admin-filter-item-date', [
        'name'=>'Updated At',
        'selectedItem'=>$selectedItem,
        'selectedItemKey'=>'updatedAt'
    ])

</div>
