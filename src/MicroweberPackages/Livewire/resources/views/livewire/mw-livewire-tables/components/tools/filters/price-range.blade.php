@php
    $theme = $component->getTheme();
@endphp

@if ($theme === 'tailwind')
    not supported
@elseif ($theme === 'bootstrap-4' || $theme === 'bootstrap-5')

    <input
        wire:model.stop="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}"
        wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
        id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
        type="text"
    />

    <div class="mb-3 mb-md-0 input-group">
        <input type="number" class="form-control" id="js-{{ $filter->getKey() }}-min-price" placeholder="Min price" />
        <input type="number" class="form-control" id="js-{{ $filter->getKey() }}-max-price" placeholder="Max Price" />
    </div>

    <script>
        let minPrice_{{ $filter->getKey() }} = document.getElementById('js-{{ $filter->getKey() }}-min-price');
        let maxPrice_{{ $filter->getKey() }} = document.getElementById('js-{{ $filter->getKey() }}-max-price');
        let priceRange = minPrice_{{ $filter->getKey() }}.value + ', ' + maxPrice_{{ $filter->getKey() }}.value;
        let priceRangeElement = document.getElementById('{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}');

        minPrice_{{ $filter->getKey() }}.onkeyup = function() {
            priceRange = minPrice_{{ $filter->getKey() }}.value + ',' + maxPrice_{{ $filter->getKey() }}.value;
            priceRangeElement.value = priceRange;
            $(priceRangeElement).trigger('change');
        };

        maxPrice_{{ $filter->getKey() }}.onkeyup = function() {
            priceRange = minPrice_{{ $filter->getKey() }}.value + ',' + maxPrice_{{ $filter->getKey() }}.value;
            priceRangeElement.value = priceRange;
            $(priceRangeElement).trigger('change');
        };

        priceRangeElement.value = priceRange;
    </script>
@endif
