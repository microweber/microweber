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

        $('.js-admin-post-tag-edit-form').submit(function (e) {
            e.preventDefault();

            $.ajax({
                url: mw.settings.api_url + 'post_tag/edit',
                type: 'post',
                data: $(this).serialize(),
                success: function(data) {
                    //  mw.reload_module_everywhere('tags');
                    mw.notification.success('<?php _e('Tag is saved!');?>');
                }
            });

        });

    });
</script>

<?php
$name = '';
$slug = '';
//$description = '';

$post_tag_id = $params['post_tag_id'];
$filter = [
    'id'=>$post_tag_id,
    'single'=>1
];
$tag = db_get('tagging_tagged', $filter);
?>

<form method="post" class="js-admin-post-tag-edit-form">

    <div class="demobox">
        <label class="mw-ui-label"><?php _e('Tag Name');?></label>
        <input type="text" name="tag_name" value="<?php echo $tag['tag_name']; ?>" class="form-control js-admin-post-tag-edit-form-tag-name">
        <div class="helptext"><?php _e('The name is how it appears on your site.');?></div>
    </div>

    <div class="demobox">
        <label class="mw-ui-label"><?php _e('Tag Slug');?></label>
        <input type="text" name="tag_slug" value="<?php echo $tag['tag_slug']; ?>" class="form-control js-admin-post-tag-edit-form-tag-slug">
        <div class="helptext"><?php _e('The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.');?></div>
    </div>

    <div class="demobox">
        <label class="mw-ui-label"><?php _e('Tag Description'); ?></label>
        <textarea name="description" class="form-control js-admin-post-tag-edit-form-tag-description"></textarea>
        <div class="helptext"><?php _e('The description is not prominent by default; however, some themes may show it.'); ?></div>
    </div>

    <input type="hidden" name="id" class="js-admin-post-tag-edit-form-id" value="<?php echo $tag['id']; ?>" />

    <button class="btn btn-success" type="submit"><i class="mw-icon-web-checkmark"></i> &nbsp; <?php _e('Save Tag');?></button>

</form>

<style>
    .js-admin-post-tags {
        margin-top:20px;
    }
</style>

<div class="js-admin-post-tags"></div>