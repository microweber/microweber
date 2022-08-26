@php
    $theme = $component->getTheme();
@endphp

@if ($theme === 'tailwind')
    <div class="rounded-md shadow-sm">
        <select
            wire:model.stop="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}"
            wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
            id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
            class="block w-full border-gray-300 rounded-md shadow-sm transition duration-150 ease-in-out focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:text-white dark:border-gray-600"
        >
            @foreach($filter->getOptions() as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
@elseif ($theme === 'bootstrap-4' || $theme === 'bootstrap-5')
    <select
        wire:model.stop="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}"
        wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
        id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
        class="form-control"
    >
        @foreach($filter->getOptions() as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>
@endif
