<?php only_admin_access(); ?>
<?php
$list_params = array();
$list_params['no_limit'] = true;
$list_params['order_by'] = "created_at desc";
$lists = newsletter_get_lists($list_params);
?>
<?php if ($lists): ?>
    <div class="table-responsive">
        <table width="100%" border="0" class="mw-ui-table" style="table-layout:fixed">
            <thead>
            <tr>
                <th><?php _e('Name'); ?></th>
                <th width="140px">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($lists as $list): ?>
                <tr>
                    <td><?php print $list['name']; ?></td>
                    <td>
                        <button class="mw-ui-btn" onclick="edit_list('<?php print $list['id']; ?>')"><?php _e('Edit'); ?></button>
                        <a class="mw-ui-btn mw-ui-btn-icon" href="javascript:;" onclick="delete_list('<?php print $list['id']; ?>')"> <span class="mw-icon-bin"></span> </a>
                   </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>