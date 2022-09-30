<div class="ms-0 ms-md-2 mb-3 mb-md-0">

{{--    <select wire:model.stop="filters.visible" class="form-control">
        <option value="">Any</option>
        <option value="1">Published</option>
        <option value="0">Unpublished</option>
    </select>--}}

    @php
        $selectedItem = [];
        if (isset($filters['visible'])) {
            $selectedItem = $filters['visible'];
        }
    @endphp

    @livewire('admin-filter-item', ['selectedItem'=>$selectedItem])


</div>
