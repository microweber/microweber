<input
    wire:model.stop="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}"
    wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
    id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
    type="text"
/>

<input type="text" value="" id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}-tags-autocomplete"
       class="form-control">

<script>
    let tagslement_{{ $filter->getKey() }} = document.getElementById('{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}');

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
        $(tagslement_{{ $filter->getKey() }}).val(tag.title);
        tagslement_{{ $filter->getKey() }}.dispatchEvent(new Event('input'));
    });

    $(tagsSelect_{{ $filter->getKey() }}).on('enterOrComma', function (e, node) {

    });

</script>
