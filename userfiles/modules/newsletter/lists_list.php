<?php only_admin_access(); ?>
<?php

$default_list = array(array(
    'id' => 0,
    'name' => _e('Default', true),)
);

$list_params = array();
$list_params['no_limit'] = true;
$list_params['order_by'] = "created_at desc";
$lists = newsletter_get_lists($list_params);




$all_lists = array();


$all_lists = $default_list;
if($lists){
    $all_lists = array_merge($default_list,$lists);
}

?>
<?php if ($all_lists): ?>
    <div class="table-responsive">
    <table width="100%" border="0" class="mw-ui-table" style="table-layout:fixed">
        <thead>
        <tr>
            <th><?php _e('Name'); ?></th>
            <th><?php _e('Subscribers'); ?></th>
            <th width="140px">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($all_lists as $list): ?>
            <tr>
                <td><?php print $list['name']; ?></td>
                <td><?php print newsletter_get_lists('count=1&list_id='.$list['id']); ?></td>
                <td>
                    <?php if ($list['id']): ?>

                    <button class="mw-ui-btn" onclick="edit_list('<?php print $list['id']; ?>')"><?php _e('Edit'); ?></button>
                    <a class="mw-ui-btn mw-ui-btn-icon" href="javascript:;" onclick="delete_list('<?php print $list['id']; ?>')"> <span class="mw-icon-bin"></span> </a>
                 <?php endif; ?>

                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div><?php else: ?>
    <b>No Lists found.</b>
<?php endif; ?>