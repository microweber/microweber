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

        var tags = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.whitespace,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            local: data
        });
        tags.initialize();
        console.log(tags)

        var node = document.querySelector('#content-tags-block');
        var tagsData = <?php print json_encode($tags_str) ?>.map(function (tag){
            return {title: tag}
        });
         $(new mw.tags({
             element: node,
             data: tagsData,
             color: 'primary',
             size:  'sm',
             inputField: true,
         }))
         .on('change', function(e, item, data){
            mw.element('[name="tag_names"]').val(data.map(function (c) {  return c.title }).join(',')).trigger('change')
        });
    });
</script>
<div class="row">
    <div class="col-12">
        <div id="content-tags-block" class="form-control"></div>
        <input type="hidden" name="tag_names" value="<?php print implode(',', $tags_str); ?>" id="tags"/>
    </div>
</div>
