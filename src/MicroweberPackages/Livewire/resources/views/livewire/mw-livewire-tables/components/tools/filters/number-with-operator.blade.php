<div class="mb-3 mb-md-0 input-group">

    <select

        wire:model.stop="{{ $component->getTableName() }}.filters.sales_operator"
        wire:key="{{ $component->getTableName() }}-filter-sales_operator"
        id="{{ $component->getTableName() }}-filter-sales_operator"

        class="form-control">
        <option value="equal">Equal</option>
        <option value="more_than">More than</option>
        <option value="lower_than">Lower than</option>
    </select>

    <input type="number" class="form-control" placeholder="Sales count"

       wire:model.stop="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}"
       wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
       id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"

    />
</div>
