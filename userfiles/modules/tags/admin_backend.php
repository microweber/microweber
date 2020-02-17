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
    $(document).ready(function () {
        var mySelect = mw.select({
            data: <?php echo json_encode($multipleSelectPosts); ?>,
            element: '.select-posts',
            multiple: true,
            autocomplete: true,
            tags: true
        });

        $(mySelect).on('change', function (event, value) {
            console.log(vaule)
        });
    });

    function editTag(tag_id) {

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
        mw.load_module('tags/edit', '#mw_admin_edit_tag_item_module', null, params);

    }

    function deleteTag(tag_id) {
        $.ajax({
            url: mw.settings.api_url + 'tag/delete',
            type: 'post',
            data: {
                tag_id: tag_id
            },
            success: function(data) {
                mw.reload_module_everywhere('tags');
                mw.notification.error('<?php _e('Tag is deleted!');?>');
            }
        });
    }
</script>

<div id="mw-admin-content" class="admin-side-content">
    <div class="mw-modules-tabs">

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

               /*     for ($i = 1; $i <= 5000; $i++) {

                        $save = db_save('content', [
                            'content_type'=>'post',
                            'sub_type'=>'post',
                            'title'=>'BLOG POST TITLE: ' . $i,
                            'parent'=>6,
                            'is_active'=>1
                        ]);

                    }*/

                    $tagging_tags = db_get('tagging_tags',[
                            'no_cache'=>false
                    ]);
                    if ($tagging_tags):
                    foreach ($tagging_tags as $tag):
                    ?>
                    <tr>
                        <td><?php echo $tag['name']; ?></td>
                        <td><?php echo $tag['slug']; ?></td>
                        <td><?php echo $tag['count']; ?></td>
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


            </div>
        </div>

        <div class="mw-accordion-item">
            <div class="mw-ui-box-header mw-accordion-title">
                <div class="header-holder">
                    <i class="mw-icon-navicon-round"></i> <?php _e('Posts'); ?>
                </div>
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content" style="min-height: 500px">

                <div class="select-posts"></div>


            </div>
        </div>

    </div>
</div>