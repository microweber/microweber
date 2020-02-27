<?php
$taggable_id = false;
if (isset($params['taggable_id'])) {
    $taggable_id = $params['taggable_id'];
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

        $('.js-admin-post-tag-add-form').submit(function (e) {
            e.preventDefault();

            $.ajax({
                url: mw.settings.api_url + 'tagging_tagged/add',
                type: 'post',
                data: $(this).serialize(),
                success: function(data) {
                    if (data.id) {
                        var postTagCloud = $('.js-post-tag-' + $('.js-admin-post-tag-add-form-post-id').val());

                        if (postTagCloud.find('.btn-tag-id-' + data.id).length == 0) {
                            postTagCloud.find('.js-post-tag-add-new').before(getTaggingTaggedButtonHtml(data.id, data.tag_name));
                        } else {
                            postTagCloud.find('.btn-tag-id-' + data.id).replaceWith(getTaggingTaggedButtonHtml(data.id, data.tag_name));
                        }

                        $('.js-admin-post-tag-add-form-id').val(data.id);
                        $('.js-admin-post-tag-add-form-taggable-id').val(data.taggable_id);

                        $('.js-admin-post-tag-messages').html('<div class="mw-ui-box mw-ui-box-content mw-ui-box-notification"><i class="fa fa-check"></i> <?php _e('Tag is added!'); ?></div>');

                        //  mw.reload_module_everywhere('tags');
                        mw.notification.success('<?php _e('Tag is added!');?>');
                    } else {
                        $('.js-admin-post-tag-messages').html('<div class="mw-ui-box mw-ui-box-content mw-ui-box-important"><i class="fa fa-times"></i> '+data.message+'</div>');
                        mw.notification.error(data.message);
                    }
                }
            });

        });

    });

    var tagsSelect = mw.select({
        element: '[name="tag_name"]',
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
    <?php if (!empty($tag['id'])) : ?>
    tagsSelect.value({id:<?php echo $tag['id']; ?>, title:'<?php echo $tag['tag_name']; ?>'});
    <?php endif; ?>
    
    $(tagsSelect).on("change", function(event, tag){
        if (tag.id) {
            $.ajax({
                url: mw.settings.api_url + 'tagging_tag/view',
                type: 'post',
                data: {tagging_tag_id: tag.id},
                success: function (data) {
                    if (data.name) { 
                        $('.js-admin-post-tag-add-form-global-tag-id').val(data.id);
                        $('.js-admin-post-tag-add-form-tag-name').val(data.name);
                    }
                }
            });
        }
    });
</script>


<form method="post" class="js-admin-post-tag-add-form">

    <div class="demobox">
        <label class="mw-ui-label"><?php _e('Tag Name');?></label>
        <input type="text" name="tag_name" value="" class="form-control js-admin-post-tag-add-form-tag-name">
        <div class="helptext"><?php _e('The name is how it appears on your site.');?></div>
    </div>


    <input type="hidden" name="taggable_id" class="js-admin-post-tag-add-form-taggable-id" value="<?php echo $taggable_id; ?>" />

    <!-- this will be filled automaticly -->
    <input type="hidden" name="tagging_tag_id" class="js-admin-post-tag-add-form-global-tag-id" value="" />


    <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> &nbsp; <?php _e('Add tag');?></button>

</form>

<style>
    .js-admin-post-tag-messages {
        margin-top: 15px;
    }
    .js-admin-post-tags {
        margin-top:20px;
    }
</style>

<div class="js-admin-post-tag-messages"></div>

<div class="js-admin-post-tags"></div>
