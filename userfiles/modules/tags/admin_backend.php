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


            </div>
        </div>

    </div>
</div>