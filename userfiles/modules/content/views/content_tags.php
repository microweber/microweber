<?php
if (!function_exists('content_tags')) {
    return;
}
only_admin_access();

if (!isset($params['content-id'])) {
    return;
}
$tags_str = content_tags($params['content-id']);

if (!$tags_str) {
    $tags_str = array();
}


$all_existing_tags = json_encode(content_tags());
if ($all_existing_tags == null) {
    $all_existing_tags = '[]';
}
?>

<script type="text/javascript">
    $(document).ready(function () {
        mw.lib.require('bootstrap3ns');
        mw.lib.require('bootstrap_tags');
    });
</script>

<div class="bootstrap3ns">
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label">
            <?php _e("Tags"); ?>
            <small class="mw-help"
                   data-help="<?php _e('Tags/Labels for this content. Use comma (,) to add multiple tags'); ?>"> (?)
            </small>
        </label>
        <input type="text" name="tags" class="mw-ui-field" value="<?php print implode(',', $tags_str); ?>" data-role="tagsinput" id="tags"/>
    </div>

    <script>
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
</div>

