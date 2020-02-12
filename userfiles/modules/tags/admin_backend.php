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

<script>

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
                mw.load_module('tags');
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
                    $filter = [

                    ];
                    $content_tags = db_get('tagging_tags', $filter);
                    foreach ($content_tags as $tag):
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

                    </tbody>
                </table>



            </div>
        </div>

    </div>
</div>