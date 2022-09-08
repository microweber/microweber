<input
    wire:model.stop="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}"
    wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
    id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
    type="text"
/>

<div id="content-tags-block"></div>
<div id="content-tags-search-block"></div>

<input type="text" value="" id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}-tags-autocomplete"
       class="form-control">

<script>

    var node = document.querySelector('#content-tags-block');
    var tagsData = <?php print json_encode([]) ?>.map(function (tag) {
        return {title: tag, id: tag}
    });
    var tags = new mw.tags({
        element: node,
        data: tagsData,
        color: 'primary',
        size: 'sm',
        inputField: false,
    })
    $(tags)
        .on('change', function (e, item, data) {
            mw.element('[name="tag_names"]').val(data.map(function (c) {
                return c.title
            }).join(',')).trigger('change')
        });


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

    $(tagsSelect).on("change", function (event, tag) {
        tags.addTag(tag);
        
        setTimeout(function () {
            tagsSelect.element.value = '';
        });
    });

    $(tagsSelect).on('enterOrComma', function (e, node) {
        tags.addTag({title: node.value, id: node.value});
        setTimeout(function () {
            node.value = '';
        })
    });

</script>
