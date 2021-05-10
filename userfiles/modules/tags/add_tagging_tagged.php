<?php
$taggable_id = false;
if (isset($_POST['taggable_id']) && $_POST['taggable_id'] !== 'false') {
    $taggable_id = $_POST['taggable_id'];
}

$taggable_ids = false;
if (isset($_POST['taggable_ids']) && !empty($_POST['taggable_ids']) && $_POST['taggable_ids'] !== 'false') {
    $taggable_ids = $_POST['taggable_ids'];
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

    .helptext {
        color: #666;
    }
    .mw-select{
        width: 100%;
    }
     form .mw-select-options{
        max-height: 220px !important;
    }
    form{
        min-height: 300px;
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
                success: function (data) {
                    if (data.status == true) {
                        var postTagCloud = $('.js-post-tag-' + $('.js-admin-post-tag-add-form-post-id').val());

                        if (postTagCloud.find('.btn-tag-id-' + data.id).length == 0) {
                            postTagCloud.find('.js-post-tag-add-new').before(getTaggingTaggedButtonHtml(data.id, data.tag_name));
                        } else {
                            postTagCloud.find('.btn-tag-id-' + data.id).replaceWith(getTaggingTaggedButtonHtml(data.id, data.tag_name));
                        }

                        $('.js-admin-post-tag-add-form-id').val(data.id);
                        // $('.js-admin-post-tag-add-form-taggable-id').val(data.taggable_id);

                        $('.js-admin-post-tag-messages').html('<div class="mw-ui-box mw-ui-box-content mw-ui-box-notification"><i class="fa fa-check"></i> <?php _e('Tag is added!'); ?></div>');


                        $('.js-admin-post-tag-add-form-global-tag-id').val('');
                        $('.js-admin-post-tag-add-form-tag-name').val('');

                        if (typeof(tagsSelect) !== 'undefined') {
                            tagsSelect.value({id: '', title: ''});
                        }

                        var i_ids;
                        for (i_ids = 0; i_ids < data.ids.length; i_ids++) {
                            getPostTags(data.ids[i_ids].taggable_id);
                        }

                        <?php if ($taggable_ids): ?>
                        <?php foreach ($taggable_ids as $taggable_id_data): ?>
                        getPostTags(<?php echo $taggable_id_data['taggable_id']; ?>);
                        <?php endforeach; ?>
                        <?php endif; ?>

                        searchTagsByKeyowrd();
if(self !== top){
                          mw.reload_module_everywhere('tags');
 mw.reload_module_everywhere('posts');
 mw.reload_module_everywhere('shop/products');
}
                        mw.notification.success('<?php _e('Tag is added!');?>');
                    } else {
                        $('.js-admin-post-tag-messages').html('<div class="mw-ui-box mw-ui-box-content mw-ui-box-important"><i class="fa fa-times"></i> ' + data.message + '</div>');
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
    tagsSelect.value({id:<?php echo $tag['id']; ?>, title: '<?php echo $tag['tag_name']; ?>'});
    <?php endif; ?>

    $(tagsSelect).on("change", function (event, tag) {
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
    <div class="form-group">
        <label class="control-label"><?php _e('Tag Name'); ?></label>
        <small class="text-muted mb-2 d-block"><?php _e('The name is how it appears on your site.'); ?></small>
        <input type="text" name="tag_name" value="" class="form-control js-admin-post-tag-add-form-tag-name">
    </div>

    <?php if ($taggable_id): ?>
        <input type="hidden" name="taggable_id" class="js-admin-post-tag-add-form-taggable-id" value="<?php echo $taggable_id; ?>"/>
    <?php endif; ?>

    <?php if ($taggable_ids): ?>
        <?php foreach ($taggable_ids as $taggable_id_data): ?>
            <input type="hidden" name="taggable_ids[]" value="<?php echo $taggable_id_data['taggable_id']; ?>"/>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- this will be filled automaticly -->
    <input type="hidden" name="tagging_tag_id" class="js-admin-post-tag-add-form-global-tag-id" value=""/>

    <button class="btn btn-success btn-sm" type="submit"><?php _e('Add tag'); ?></button>
</form>

<div class="js-admin-post-tag-messages mt-2"></div>

<div class="js-admin-post-tags mt-3"></div>
