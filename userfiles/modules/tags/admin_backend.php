<?php
/**
 * Dev: Bozhidar Slaveykov
 * Emai: bobi@microweber.com
 * Date: 11/18/2019
 * Time: 10:26 AM
 */
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<style>
    .mw-select {
        width: 100%;
    }
    .select-posts {
        width: 500px;
    }
    .mw-ui-btn-nav-tabs > .mw-ui-btn:last-child{
        float: right;
        border-left-width: 1px;
        display: none;
    }
    .mw-ui-btn-nav-tabs{
        width: 100%;
    }

</style>

<script>
    mw.lib.require('bootstrap4');


    function editTaggingTag(tagging_tag_id, taggable_ids) {

        var modal_title = 'Add new tag';
        if (tagging_tag_id) {
            modal_title = 'Edit tag';
        }

        mw_admin_edit_tag_modal = mw.dialog({
            content: '<div id="mw_admin_edit_tagging_tag_item_module">Loading...</div>',
            title: modal_title,
            id: 'mw_admin_edit_tagging_tag_item_popup_modal'
        });

        var params = {};
        params.tagging_tag_id = tagging_tag_id;
        params.taggable_ids = taggable_ids;

        mw.load_module('tags/edit_tagging_tag', '#mw_admin_edit_tagging_tag_item_module', null, params);

    }

    function deleteTaggingTag(tagging_tag_id) {
        mw.tools.confirm(mw.msg.del, function () {
            $.ajax({
                url: mw.settings.api_url + 'tagging_tag/delete',
                type: 'post',
                data: {
                    tagging_tag_id: tagging_tag_id
                },
                success: function (data) {
                    $('.btn-tag-id-' + tagging_tag_id).remove();
                    //mw.reload_module_everywhere('tags');

                    selected_taggable_items = getSelectedTaggableItems();
                    if (selected_taggable_items) {
                        for (i = 0; i < selected_taggable_items.length; i++) {
                            getPostTags(selected_taggable_items[i].post_id);
                        }
                    }

                    mw.notification.error('<?php _e('Tag is deleted!');?>');
                }
            });
        });
    }

    function addTaggingTagged(taggable_id = false, taggable_ids = false) {

        var modal_title = 'Add new tag';

        mw_admin_edit_tag_modal = mw.dialog({
            content: '<div id="mw_admin_add_tagging_tagged_item_module">Loading...</div>',
            title: modal_title,
            id: 'mw_admin_add_tagging_tagged_item_popup_modal'
        });

        var params = {}
        params.taggable_id = taggable_id;
        params.taggable_ids = taggable_ids;

        mw.load_module('tags/add_tagging_tagged', '#mw_admin_add_tagging_tagged_item_module', null, params);

    }

    function deleteTaggingTagged(tagging_tagged_id) {
        mw.tools.confirm(mw.msg.del, function () {
            $.ajax({
                url: mw.settings.api_url + 'tagging_tagged/delete',
                type: 'post',
                data: {
                    tagging_tagged_id: tagging_tagged_id,
                },
                success: function (data) {
                    $('.btn-tag-id-' + tagging_tagged_id).remove();
                    //mw.reload_module_everywhere('tags');
                    mw.notification.error('<?php _e('Post tag is deleted!');?>');
                }
            });
        });
    }

    function showPostsWithTags(instance){
        mw.modal({
            content: '<div id="mw_admin_preview_module_content_with_tags"></div>',
            title: 'View content with tags',
            width: 1000,
            height: 600,
            id: 'mw_admin_preview_module_modal'
        });

        var params = {}
  //      params.tag_id = $id;
        params.tags = $(instance).attr('data-slug');
        params.no_toolbar = 1;

        mw.load_module('content/manager', '#mw_admin_preview_module_content_with_tags', null, params);
    }
</script>

<?php
sync_tags();
?>

<div id="mw-admin-content" class="admin-side-content">


    <div class="mw-modules-tabs">

        <div class="mw-accordion-item">
            <div class="mw-ui-box-header mw-accordion-title">
                <div class="header-holder">
                    <i class="mw-icon-navicon-round"></i> <?php _e('Posts'); ?>
                </div>
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content" style="min-height: 500px">

                <module type="tags/manage_tagging_tagged" />

            </div>
        </div>


        <div class="mw-accordion-item">
            <div class="mw-ui-box-header mw-accordion-title">
                <div class="header-holder">
                    <i class="mw-icon-navicon-round"></i> <?php _e('Global Tags'); ?>
                </div>
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">

                <div class="mw-flex-col-xs-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div style="font-weight: bold;">Search tags</div>
                            <div class="input-group">
                                <input type="text" class="form-control js-search-tags-keyword" placeholder="Keyword...">
                                <div class="input-group-append">
                                    <button class="btn btn-success js-search-posts-submit" type="button">Search</button>
                                </div>
                            </div>
                            <span class="mb-3">You can search multiple tags seperated by coma.</span>
                        </div>
                        <div class="col-md-6" style="padding-top: 17px">
                            <button class="btn btn-success pull-right" onclick="editTaggingTag(false);"><i class="fa fa-plus"></i> Create new global tag</button>

                        </div>

                        <div class="col-md-12">
                        <div class="card" style="margin-top:15px">
                            <div class="card-header">
                                Global tags
                            </div>
                            <div class="card-body">
                                <div class="js-all-tags"></div>
                            </div>
                        </div>
                    </div>

                    </div>
                </div>

            </div>
        </div>

        <div class="mw-accordion-item">
            <div class="mw-ui-box-header mw-accordion-title">
                <a href="#"><i class="fa fa-info-circle"></i> <?php _e('How to use this module?'); ?></a>
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">

                How to  ?

            </div>
        </div>

    </div>

    <br />

    <module type="help/modal_with_button" for_module="tags" />

</div>

