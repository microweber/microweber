<?php

$check = false;
if (isset($params['content_id'])){
    $check = db_get('count=1&table=content_revisions_history&rel_type=content&field=content&rel_id=' . $params['content_id']);

}

?>
<?php if ($check > 1){ ?>
    <script>
        function mw_admin_edit_content_revisions_item_popup(content_id) {
            mw_admin_edit_content_revisions_tax_item_popup_modal_opened = mw.modal({
                content: '<div id="mw_admin_edit_content_revisions_tax_item_popup_modal_module"></div>',
                title: 'Content revisions',
                id: 'mw_admin_edit_content_revisions_tax_item_popup_modal'
            });

            var params = {}
            params.content_id = content_id;
            mw.load_module('editor/content_revisions/list_for_content', '#mw_admin_edit_content_revisions_tax_item_popup_modal_module', null, params);
        }
    </script>
    <small> Revisions: <a
            href="javascript:mw_admin_edit_content_revisions_item_popup('<?php print $params['content_id'] ?>')"><?php print $check ?></a>
    </small>
<?php } ?>