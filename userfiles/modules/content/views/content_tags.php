<?php
if (!function_exists('content_tags')) {
    return;
}
$tags_str = array();

must_have_access();

if (!isset($params['content-id'])) {
    return;
}

if ($params['content-id']) {
    $tags_str = content_tags($params['content-id']);
}

if (!$tags_str) {
    $tags_str = array();
}


$all_existing_tags = json_encode(content_tags());
if ($all_existing_tags == null) {
    $all_existing_tags = '[]';
}
?>


<script type="text/javascript">
    mw.lib.require('bootstrap_tags');

    $(document).ready(function () {
        var data = <?php print $all_existing_tags; ?>;


        var node = document.querySelector('#content-tags-block');
        var nodesearch = document.querySelector('#content-tags-search-block');

        var tagsData = <?php print json_encode($tags_str) ?>.map(function (tag){
            return {title: tag, id: tag}
        });
        var tags = new mw.tags({
            element: node,
            data: tagsData,
            color: 'primary',
            size:  'sm',
            inputField: false,
        })
      $(tags)
         .on('change', function(e, item, data){
            mw.element('[name="tag_names"]').val(data.map(function (c) {  return c.title }).join(',')).trigger('change')
        });


        var tagsSelect = mw.select({
            element: nodesearch,
            multiple: false,
            autocomplete: true,
            tags: false,
            placeholder: '<?php _ejs('Add tag') ?>',
            ajaxMode: {
                paginationParam: 'page',
                searchParam: 'keyword',
                endpoint: mw.settings.api_url + 'tagging_tag/autocomplete',
                method: 'get'
            }
        });


        $(tagsSelect).on("change", function (event, tag) {
            tags.addTag(tag)
            setTimeout(function () {tagsSelect.element.querySelector('input').value = '';})
        });

        $(tagsSelect).on('enterOrComma', function (e, node){
            tags.addTag({title: node.value, id: node.value});
            setTimeout(function () {node.value = '';})
        });



    });
</script>
<div class="row">
    <div class="col-12">
        <div id="content-tags-block"></div>
        <div id="content-tags-search-block"></div>
        <input type="hidden" name="tag_names" value="<?php print implode(',', $tags_str); ?>" id="tags"/>
    </div>
</div>
