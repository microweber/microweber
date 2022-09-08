<input
    wire:model.stop="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}"
    wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
    id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
    type="text"
/>

<input type="text" value="" id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}-tags-autocomplete"
       class="form-control">

<script>
    document.addEventListener('livewire:load', function () {

        let tagsSelected_{{ $filter->getKey() }} = [];
        let tagsElement_{{ $filter->getKey() }} = document.getElementById('{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}');

        if (tagsElement_{{ $filter->getKey() }}.value != '') {
            tagsSelected_{{ $filter->getKey() }} = tagsElement_{{ $filter->getKey() }}.value.split(",");
        }

        var tagsSelect_{{ $filter->getKey() }} = mw.select({
            element: '#{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}-tags-autocomplete',
            multiple: false,
            autocomplete: true,
            tags: false,
            placeholder: '',
            ajaxMode: {
                paginationParam: 'page',
                searchParam: 'keyword',
                endpoint: mw.settings.api_url + 'tagging_tag/autocomplete',
                method: 'get'
            }
        });

        $(tagsSelect_{{ $filter->getKey() }}).on("change", function (event, tag) {

            tagsSelected_{{ $filter->getKey() }}.push(tag.title);
            let tagsSelectedSeperated_{{ $filter->getKey() }} = tagsSelected_{{ $filter->getKey() }}.join(",");

            $(tagsElement_{{ $filter->getKey() }}).val(tagsSelectedSeperated_{{ $filter->getKey() }});

            tagsElement_{{ $filter->getKey() }}.dispatchEvent(new Event('input'));
        });
    });

</script>
