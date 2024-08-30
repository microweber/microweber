<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card-body mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class=" ">
        <script>
            function editTaggingTag(tagging_tag_id, taggable_ids) {
                var modal_title = 'Add new tag';
                if (tagging_tag_id) {
                    modal_title = 'Edit tag';
                }

                mw_admin_edit_tag_modal = mw.dialog({
                    content: '<div id="mw_admin_edit_tagging_tag_item_module"><?php _ejs("Loading"); ?>...</div>',
                    title: modal_title,
                    id: 'mw_admin_edit_tagging_tag_item_popup_modal'
                });

                var params = {};
                params.tagging_tag_id = tagging_tag_id;
                params.taggable_ids = taggable_ids;

                mw.load_module('tags/edit_tagging_tag', '#mw_admin_edit_tagging_tag_item_module', function(){
                    mw_admin_edit_tag_modal.center();
                }, params);
            }

            function deleteTaggingTag(tagging_tag_id) {
                mw.confirm(mw.msg.del, function () {
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
                            if (typeof selected_taggable_items !== 'undefined' && selected_taggable_items.length > 0) {
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
                    content: '<div id="mw_admin_add_tagging_tagged_item_module"><?php _ejs("Loading"); ?>...</div>',
                    title: modal_title,
                    id: 'mw_admin_add_tagging_tagged_item_popup_modal',
                    height: 'auto'
                });

                var params = {}
                params.taggable_id = taggable_id;
                params.taggable_ids = taggable_ids;

                mw.load_module('tags/add_tagging_tagged', '#mw_admin_add_tagging_tagged_item_module', null, params);
            }

            function deleteTaggingTagged(tagging_tagged_id) {
                mw.confirm(mw.msg.del, function () {
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

            function showPostsWithTags(instance) {
                mw.dialog({
                    content: '<div id="mw_admin_preview_module_content_with_tags"></div>',
                    title: 'View content with tags',
                    width: 1000,
                    height: 600,
                    id: 'mw_admin_preview_module_modal'
                });

                var params = {}
                params.tags = $(instance).attr('data-slug');
                params.no_toolbar = 1;

                mw.load_module('content/manager', '#mw_admin_preview_module_content_with_tags', null, params);
            }
        </script>

        <?php sync_tags(); ?>

        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs  active" data-bs-toggle="tab" href="#list">  <?php _e("Tagged content"); ?></a>
            <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs " data-bs-toggle="tab" href="#global-tags"> <?php _e('Global Tags'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="list">
                <module type="tags/manage_tagging_tagged"/>
            </div>

            <div class="tab-pane fade" id="global-tags">

                <div class="card">
                  <div class="card-body">
                      <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label class="form-label mb-0"><?php _e("Search tags");?></label>
                                  <small class="d-block text-muted mb-2"><?php _e("You can search multiple tags separated by coma");?>.</small>

                                  <div class="row p-0 mb-4">
                                      <div class="col">
                                          <input type="text" class="form-control js-search-tags-keyword" placeholder="<?php _e("Keyword");?>...">

                                      </div>
                                      <div class="col-auto">
                                          <a href="#" class="btn btn-icon js-search-posts-submit" aria-label="Button">
                                              <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
                                          </a>
                                      </div>
                                  </div>

                              </div>
                          </div>

                          <div class="col-md-6 text-end text-right">
                              <button class="btn btn-link " onclick="editTaggingTag(false);"><?php _e("Create new global tag");?></button>
                          </div>

                          <div class="col-md-12">
                              <div class="card  bg-azure-lt">
                                  <div class="card-header mt-4">
                                      <label class="form-label"><?php _e("Global tags");?></label>
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
        </div>

        <module type="help/modal_with_button" for_module="tags"/>
    </div>
</div>

