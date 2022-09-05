@php
    $theme = $component->getTheme();
@endphp

@if ($theme === 'tailwind')
    not supported
@elseif ($theme === 'bootstrap-4' || $theme === 'bootstrap-5')

    <input
        wire:model="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}"
        wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
        id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
        type="text"
        class="form-control"
    />

    <div class="mb-3 mb-md-0 input-group">
        <input type="number" class="form-control" wire:model="state.minPrice" id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}-min" placeholder="Min price" />
        <input type="number" class="form-control" wire:model="state.maxPrice" id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}-max" placeholder="Max Price" />
    </div>
@endif
