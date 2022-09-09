{{--
<div class="form-check">
    <input
        type="checkbox"
        id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}-select-all"
        wire:input="selectAllFilterOptions('{{ $filter->getKey() }}')"
        {{ count($component->getAppliedFilterWithValue($filter->getKey()) ?? []) === count($filter->getOptions()) ? 'checked' : ''}}
        class="form-check-input"
    >
    <label class="form-check-label" for="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}-select-all">@lang('All')</label>
</div>

@foreach($filter->getOptions() as $key => $value)
    <div class="form-check" wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}-multiselect-{{ $key }}">
        <input
            class="form-check-input"
            type="checkbox"
            id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}-{{ $loop->index }}"
            value="{{ $key }}"
            wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}-{{ $loop->index }}"
            wire:model.stop="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}"
        >
        <label class="form-check-label" for="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}-{{ $loop->index }}">{{ $value }}</label>
    </div>
@endforeach
--}}


@foreach($filter->getOptions() as $key => $value)
    <div wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}-multiselect-{{ $key }}">
        <input
            class="form-control"
            type="text"
            id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}-{{ $loop->index }}"

            wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}-{{ $loop->index }}"
            wire:model.stop="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}"
        >
    </div>
@endforeach
