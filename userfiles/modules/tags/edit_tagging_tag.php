<?php
$name = '';
$slug = '';
$description = '';

$tagging_tag_id = $params['tagging_tag_id'];
$filter = [
    'id'=>$tagging_tag_id,
    'single'=>1
];
$tag = db_get('tagging_tags', $filter);
if ($tag) {
    $name = $tag['name'];
    $slug = $tag['slug'];
    $description = $tag['description'];
}
?>

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
                url: mw.settings.api_url + 'tagging_tag/edit',
                type: 'post',
                data: $(this).serialize(),
                success: function(data) {

                    if (data.name) {

                        if ($('.js-admin-tags').find('.btn-tag-id-' + data.id).length == 0) {
                            $('.js-admin-tags').append(getTagButtonHtmlInForm(data.id, data.name, data.slug));
                        } else {
                            $('.btn-tag-id-' + data.id).replaceWith(getTagButtonHtmlInForm(data.id, data.name, data.slug));
                        }

                        $('.js-admin-tag-edit-form').find('.js-clear-after-save').each(function (e) {
                            $(this).val('');
                        });

                        searchTagsByKeyowrd();

                        if (typeof(tagsSelect) != "undefined") {
                            tagsSelect.value({});
                        }

                        //  mw.reload_module_everywhere('tags');
                        mw.notification.success('<?php _e('Tag is saved!');?>');
                        $('.js-admin-tag-edit-messages').html('<div class="alert alert-success"><?php _e('Tag is saved!'); ?></div>');
                    } else if (data.message) {
                        mw.notification.error(data.message);
                        $('.js-admin-tag-edit-messages').html('<div class="alert alert-danger">' + data.message + '</div>');
                    } else {
                        mw.notification.error('<?php _e('Please, fill all fields.'); ?>');
                        $('.js-admin-tag-edit-messages').html('<div class="alert alert-danger"><?php _e('Please, fill all fields.'); ?></div>');
                    }
                }
            });

        });

    });

    function getTagButtonHtmlInForm(id,name,slug) {

        // remove html from string before display
        name =  $('<p>' + name + '</p>').text();

         var html = '<div class="btn-group tag mb-2 mr-1 btn-tag-id-'+id+'" role="group">' +
            '    <button type="button" class="btn-sm icon-left no-hover btn btn-secondary" onClick="editTaggingTagReplaceForm('+id+')"><i class="mdi mdi-tag"></i> ' + name + '</button>' +
            '    <button type="button" class="btn btn-primary btn-sm btn-icon" onClick="editTaggingTagReplaceForm('+id+')"><i class="mdi mdi-pencil"></i></button>' +
            '    <button type="button" class="btn btn-primary btn-sm btn-icon" onClick="deleteTaggingTag('+id+')"><i class="mdi mdi-close"></i></button>' +
            '</div>';

        return html;
    }

    function editTaggingTagReplaceForm(tagging_tag_id) {
        $.ajax({
            url: mw.settings.api_url + 'tagging_tag/view',
            type: 'get',
            data: {
                tagging_tag_id:tagging_tag_id
            },
            success: function (data) {
                if (data.name) {

                    if (typeof(tagsSelect) != "undefined") {
                        tagsSelect.value({id: data.id, title: data.name});
                    }

                    $('.js-admin-tag-edit-form-tag-name').val(data.name);
                    $('.js-admin-tag-edit-form-tag-slug').val(data.slug);
                    $('.js-admin-tag-edit-form-tag-description').val(data.description);
                    $('.js-admin-tag-edit-form-tag-id').val(data.id);
                }

            }
        });
    }
</script>

<form method="post" class="js-admin-tag-edit-form">
    <div class="form-group">
        <label class="control-label"><?php _e('Tag Name');?></label>
        <small class="text-muted mb-2 d-block"><?php _e('The name is how it appears on your site.');?></small>
        <input type="text" name="name" value="<?php echo $name; ?>" class="form-control js-admin-tag-edit-form-tag-name js-clear-after-save">
    </div>

    <div class="form-group" <?php if ($tag): ?> style="display: none;" <?php endif; ?>>
        <label class="control-label"><?php _e('Tag Slug');?></label>
        <small class="text-muted mb-2 d-block"><?php _e('The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.');?></small>
        <input type="text" name="slug" value="<?php echo $slug; ?>" class="form-control js-admin-tag-edit-form-tag-slug js-clear-after-save">
    </div>


    <div class="form-group">
        <label class="control-label"><?php _e('Tag Description'); ?></label>
        <small class="text-muted mb-2 d-block"><?php _e('The description is not prominent by default; however, some themes may show it.'); ?></small>
        <textarea name="description" class="form-control js-admin-tag-edit-form-tag-description js-clear-after-save"><?php echo $description; ?></textarea>
    </div>

    <input type="hidden" name="tagging_tag_id" class="js-admin-tag-edit-form-tag-id" value="<?php if ($tag): echo $tag['id']; endif; ?>" />

    <button class="btn btn-success btn-sm" type="submit"><?php _e('Save Tag');?></button>
</form>

<div class="js-admin-tag-edit-messages" style="padding-top: 15px"></div>

<div class="js-admin-tags mt-3"></div>
