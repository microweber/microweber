<div class="col-12 col-sm-6 col-md-3 col-lg-3 mb-4">
    <label class="d-block">
        Tags
    </label>

    <div class="mb-3 mb-md-0">
        @php
            $selectedItems = [];
            if (isset($filters['tags'])) {
                $selectedItems = explode(',', $filters['tags']);
            }
        @endphp
        @livewire('admin-filter-item-tags', ['selectedItems'=>$selectedItems])
    </div>

</div>
