<div class="ms-0 ms-md-2 mb-3 mb-md-0">
    @php
        $selectedItems = [];
        if (isset($filters['userIds'])) {
            $selectedItems = explode(',', $filters['userIds']);
        }
    @endphp
    @livewire('admin-filter-item-users', ['selectedItems'=>$selectedItems])
</div>
