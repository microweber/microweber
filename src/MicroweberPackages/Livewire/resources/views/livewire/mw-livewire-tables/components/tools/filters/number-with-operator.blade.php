<div class="mb-3 mb-md-0 input-group">
    <select class="form-control"

        wire:model.stop="{{ $component->getTableName() }}.filters.operator.{{ $filter->getKey() }}"
        wire:key="{{ $component->getTableName() }}-filter-operator-{{ $filter->getKey() }}"
        id="{{ $component->getTableName() }}-filter-operator-{{ $filter->getKey() }}"


    >
        <option value="=">Equal</option>
        <option value=">">More then</option>
        <option value="<">Lower then</option>
    </select>
    <input type="number" class="form-control" placeholder="Sales count"

       wire:model.stop="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}"
       wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
       id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"

    />
</div>
