@php
    $theme = $component->getTheme();
@endphp

@if ($theme === 'tailwind')
    not supported
@elseif ($theme === 'bootstrap-4' || $theme === 'bootstrap-5')

    <div class="mb-3 mb-md-0 input-group">

        <input type="text"
               wire:model.stop="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}[min]"
               wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}-min"
               id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}-min" >

        <input type="number" class="form-control" placeholder="Min price" />
        <input type="number" class="form-control" placeholder="Max Price" />

    </div>

@endif
