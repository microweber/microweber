<div class="col-12 col-sm-6 col-md-3 col-lg-3 mb-4">
    <label class="d-block">
        Authors
    </label>

    <div class="mb-3 mb-md-0">
        @php
            $selectedItems = [];
            if (isset($filters['userIds'])) {
                $selectedItems = explode(',', $filters['userIds']);
            }
        @endphp
        @livewire('admin-filter-item-users', ['selectedItems'=>$selectedItems])
    </div>
</div>
