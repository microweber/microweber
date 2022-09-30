<div class="ms-0 ms-md-2 mb-3 mb-md-0">

    @php
        $selectedItems = [];
        if (isset($filters['tags'])) {
            $selectedItems = explode(',', $filters['tags']);
        }
    @endphp
    @livewire('admin-filter-item-tags', ['selectedItems'=>$selectedItems])

</div>
