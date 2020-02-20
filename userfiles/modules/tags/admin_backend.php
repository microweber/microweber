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

<?php
$multipleSelectPosts = [];
$posts = get_products();
foreach ($posts as $post) {
    $multipleSelectPosts[] = [
            'id'=>$post['id'],
            'title'=>$post['title']
    ];
}
?>

<style>
    .mw-select {
        width: 500px;
    }
    .select-posts {
        width: 500px;
    }
</style>

<script>
    mw.lib.require('bootstrap4');


    function editTag(tag_id, post_ids) {

        var modal_title = 'Add new tag';
        if (tag_id) {
            modal_title = 'Edit tag';
        }

        mw_admin_edit_tag_modal = mw.modal({
            content: '<div id="mw_admin_edit_tag_item_module">Loading...</div>',
            title: modal_title,
            id: 'mw_admin_edit_tag_item_popup_modal'
        });

        var params = {}
        params.tag_id = tag_id;
        params.post_ids = post_ids;
        mw.load_module('tags/edit', '#mw_admin_edit_tag_item_module', null, params);

    }

    function deleteTag(tag_id, post_id = false) {
        $.ajax({
            url: mw.settings.api_url + 'tag/delete',
            type: 'post',
            data: {
                tag_id: tag_id,
                post_id: post_id,
            },
            success: function(data) {
                $('.btn-tag-id-' + tag_id).remove();
                //mw.reload_module_everywhere('tags');

                selected_posts = getSelectedPosts();
                if (selected_posts) {
                    for (i = 0; i < selected_posts.length; i++) {
                        getPostTags(selected_posts[i].post_id);
                    }
                }

                mw.notification.error('<?php _e('Tag is deleted!');?>');
            }
        });
    }

    function editPostTag(post_tag_id, post_id = false) {

        var modal_title = 'Add new post tag';
        if (post_tag_id) {
            modal_title = 'Edit post tag';
        }

        mw_admin_edit_tag_modal = mw.modal({
            content: '<div id="mw_admin_edit_tag_item_module">Loading...</div>',
            title: modal_title,
            id: 'mw_admin_edit_tag_item_popup_modal'
        });

        var params = {}
        params.post_tag_id = post_tag_id;
        params.post_id = post_id;
        mw.load_module('tags/edit_post_tag', '#mw_admin_edit_tag_item_module', null, params);

    }

    function deletePostTag(post_tag_id) {
        $.ajax({
            url: mw.settings.api_url + 'post_tag/delete',
            type: 'post',
            data: {
                post_tag_id: post_tag_id,
            },
            success: function(data) {
                $('.btn-tag-id-' + post_tag_id).remove();
                //mw.reload_module_everywhere('tags');
                mw.notification.error('<?php _e('Post tag is deleted!');?>');
            }
        });
    }

    function showPostsWithTags($slug){
        mw.modal({
            content: '<div id="mw_admin_preview_module_content_with_tags"></div>',
            title: 'View content with tags',
            width: 1000,
            height: 600,
            id: 'mw_admin_preview_module_modal'
        });

        var params = {}
  //      params.tag_id = $id;
        params.tags = $slug;
        params.no_toolbar = 1;

        mw.load_module('content/manager', '#mw_admin_preview_module_content_with_tags', null, params);
    }
</script>

<div id="mw-admin-content" class="admin-side-content">
    <div class="mw-modules-tabs">

        <div class="mw-accordion-item">
            <div class="mw-ui-box-header mw-accordion-title">
                <div class="header-holder">
                    <i class="mw-icon-navicon-round"></i> <?php _e('Posts'); ?>
                </div>
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content" style="min-height: 500px">

                <module type="tags/manage_posts_and_tags" />

            </div>
        </div>


        <div class="mw-accordion-item">
            <div class="mw-ui-box-header mw-accordion-title">
                <div class="header-holder">
                    <i class="mw-icon-navicon-round"></i> <?php _e('Tags'); ?>
                </div>
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">

                <button type="button" onclick="editTag(false);" class="mw-ui-btn mw-ui-btn-info"> <i class="mw-icon-web-promotion"></i> &nbsp; Add New Tag</button>

                <br />
                <br />

                <table class="mw-ui-table table-style-2" width="100%" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Count</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    $paging_param = 'tags_current_page';

                   $tagging_tags_pages = db_get('tagging_tags', [
                        'page_count'=>1
                   ]);

                   $cur_page = 1;
                   $cur_page_url = url_param($paging_param, true);
                   if($cur_page_url){
                       $cur_page = intval($cur_page_url);
                   }
                    $tagging_tags = db_get('tagging_tags', [
                        'current_page'=>$cur_page,
                        'paging_param'=>$paging_param,
                     ]);


                    if ($tagging_tags):
                    foreach ($tagging_tags as $tag):
                    $tag['content_count'] = 0;


                    $count =   db_get('tagging_tagged', [
                        'tag_slug'=>$tag['slug'],
                        'count'=>1
                    ]);

                    if($count){
                        $tag['content_count'] = $count;

                    }

                    ?>
                    <tr>
                        <td><?php echo $tag['name']; ?></td>
                        <td><?php echo $tag['slug']; ?></td>

                        <td><a href="javascript:void();" onclick="showPostsWithTags('<?php echo $tag['slug']; ?>')"><?php echo $tag['content_count']; ?></a></td>
                        <td>
                            <button onclick="editTag(<?php echo $tag['id']; ?>);" class="mw-ui-btn"><span class="mw-icon-edit"></span></button>
                            <button onclick="deleteTag(<?php echo $tag['id']; ?>);" class="mw-ui-btn"><span class="mw-icon-bin"></span></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <?php else: ?>

                    <?php endif; ?>

                    </tbody>
                </table>

                <br />

                <?php if (isset($tagging_tags_pages) and $tagging_tags_pages > 1 and isset($paging_param)): ?>
                    <module type="pagination" template="mw" pages_count="<?php echo $tagging_tags_pages; ?>" paging_param="<?php echo $paging_param; ?>" />
                <?php endif; ?>

            </div>
        </div>

    </div>
</div>