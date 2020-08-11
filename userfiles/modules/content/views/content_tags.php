<?php
if (!function_exists('content_tags')) {
    return;
}
$tags_str = array();

only_admin_access();

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

        $('input[name="tags"]').tagsinput({
            allowDuplicates: false,
            typeaheadjs: {
                name: "tags",
                source: tags.ttAdapter()
            },
            freeInput: true
        });
    });
</script>

<div class="row">
    <div class="col-12">
        <div class="form-group">
            <input class="form-control form-control-sm" type="text" placeholder="healthy, beauty, travel"/>
        </div>

        <input type="text" name="tags" data-role="tagsinput" value="<?php print implode(',', $tags_str); ?>" placeholder="Separate options with a comma" id="tags"/>
    </div>
</div>