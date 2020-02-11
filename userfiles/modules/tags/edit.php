<style>
    .demobox {
        position: relative;
        padding: 10px 0px;
    }
    .mw-ui-field {
        width: 100%;
    }
    .helptext{
        color: #666;
    }
</style>

<script>
    $(document).ready(function () {

        $('.js-admin-tag-edit-form').submit(function (e) {
            e.preventDefault();

            $.ajax({
                url: mw.settings.api_url + 'tag/edit',
                type: 'post',
                data: $(this).serialize(),
                success: function(data) {
                    mw.load_module('tags');

                }
            });

        });

    });
</script>

<?php
$name = '';
$slug = '';
$description = '';

$tag_id = $params['tag_id'];
$filter = [
    'id'=>$tag_id,
    'single'=>1
];
$tag = db_get('tagging_tags', $filter);
if ($tag) {
    $name = $tag['name'];
    $slug = $tag['slug'];
}
?>

<form method="post" class="js-admin-tag-edit-form">

    <div class="demobox">
        <label class="mw-ui-label"><?php _e('Name');?></label>
        <input type="text" name="name" value="<?php echo $name; ?>" class="mw-ui-field">
        <div class="helptext"><?php _e('The name is how it appears on your site.');?></div>
    </div>

    <div class="demobox">
        <label class="mw-ui-label"><?php _e('Slug');?></label>
        <input type="text" name="slug" value="<?php echo $slug; ?>" class="mw-ui-field">
        <div class="helptext"><?php _e('The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.');?></div>
    </div>

    <div class="demobox">
        <label class="mw-ui-label"><?php _e('Description');?></label>
        <textarea name="description" class="mw-ui-field"></textarea>
        <div class="helptext"><?php _e('The description is not prominent by default; however, some themes may show it.');?></div>
    </div>

    <?php if ($tag): ?>
        <input type="hidden" name="tag_id" value="<?php echo $tag['id']; ?>" />
    <?php endif; ?>

    <button class="mw-ui-btn mw-ui-btn-info" type="submit"><i class="mw-icon-web-checkmark"></i> &nbsp; <?php _e('Save Tag');?></button>

</form>