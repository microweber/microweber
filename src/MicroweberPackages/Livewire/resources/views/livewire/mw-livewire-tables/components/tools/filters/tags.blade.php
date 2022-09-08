
<input
    wire:model.stop="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}"
    wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
    id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
    type="hidden"
/>


<input type="text" value="" id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}-tags-autocomplete" class="form-control">


<script>
    var tagsSelect = mw.select({
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
</script>
