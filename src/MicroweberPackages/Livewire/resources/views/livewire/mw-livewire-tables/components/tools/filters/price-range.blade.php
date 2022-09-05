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
        type="hidden"
    />

    <div class="mb-3 mb-md-0 input-group">
        <input type="number" class="form-control" id="js-{{ $filter->getKey() }}-min-price" placeholder="Min price" />
        <input type="number" class="form-control" id="js-{{ $filter->getKey() }}-max-price" placeholder="Max Price" />
    </div>

    <script>
        document.addEventListener('livewire:load', function () {

            let minPrice_{{ $filter->getKey() }} = document.getElementById('js-{{ $filter->getKey() }}-min-price');
            let maxPrice_{{ $filter->getKey() }} = document.getElementById('js-{{ $filter->getKey() }}-max-price');
            let priceRange_{{ $filter->getKey() }} = minPrice_{{ $filter->getKey() }}.value + ', ' + maxPrice_{{ $filter->getKey() }}.value;
            let priceRangeElement_{{ $filter->getKey() }} = document.getElementById('{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}');

            const priceRangeExp_{{ $filter->getKey() }} = priceRangeElement_{{ $filter->getKey() }}.value.split(",");
            if (priceRangeExp_{{ $filter->getKey() }}) {
                if (priceRangeExp_{{ $filter->getKey() }}[0]) {
                    minPrice_{{ $filter->getKey() }}.value = priceRangeExp_{{ $filter->getKey() }}[0];
                }
                if (priceRangeExp_{{ $filter->getKey() }}[1]) {
                    maxPrice_{{ $filter->getKey() }}.value = priceRangeExp_{{ $filter->getKey() }}[1];
                }
            }

            minPrice_{{ $filter->getKey() }}.onkeyup = function() {
                priceRange_{{ $filter->getKey() }} = minPrice_{{ $filter->getKey() }}.value + ',' + maxPrice_{{ $filter->getKey() }}.value;
                priceRangeElement_{{ $filter->getKey() }}.value = priceRange_{{ $filter->getKey() }};
                priceRangeElement_{{ $filter->getKey() }}.dispatchEvent(new Event('input'));
            };

            maxPrice_{{ $filter->getKey() }}.onkeyup = function() {
                priceRange_{{ $filter->getKey() }} = minPrice_{{ $filter->getKey() }}.value + ',' + maxPrice_{{ $filter->getKey() }}.value;
                priceRangeElement_{{ $filter->getKey() }}.value = priceRange_{{ $filter->getKey() }};
                priceRangeElement_{{ $filter->getKey() }}.dispatchEvent(new Event('input'));
            };
        });
    </script>
@endif
