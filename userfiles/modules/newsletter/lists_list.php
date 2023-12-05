<?php must_have_access(); ?>
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
if ($lists) {
    $all_lists = array_merge($default_list, $lists);
}
?>
<?php if ($all_lists): ?>

    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4><?php _e('Your lists'); ?></h4>
        </div>
        <div>
            <a href="javascript:;" class="btn btn-outline-primary mb-3" onclick="edit_list();">
                <i class="mdi mdi-plus"></i> <?php _e('Add new list'); ?>
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th class="font-weight-bold"><?php _e('Name'); ?></th>
                    <th class="font-weight-bold"><?php _e('Subscribers'); ?></th>
                    <th class="font-weight-bold" width="140px"><?php _e('Action'); ?></th>
                </tr>
            </thead>

            <tbody class="small">
                <?php foreach ($all_lists as $list): ?>
                    <tr>
                        <td><?php print $list['name']; ?></td>
                        <td><?php print count(newsletter_get_subscribers_for_list($list['id'])); ?></td>
                        <td>
                            <?php if ($list['id']): ?>
                                <button class="btn btn-outline-primary btn-sm" onclick="edit_list('<?php print $list['id']; ?>')"><?php _e('Edit'); ?></button>
                                <a class="btn btn-outline-danger btn-sm" href="javascript:;" onclick="delete_list('<?php print $list['id']; ?>')"><i class="mdi mdi-trash-can-outline"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="icon-title justify-content-center">
        <i class="mdi mdi-email-check-outline"></i> <h5 class="mb-0"><?php _e("No lists found"); ?></h5>
    </div>
<?php endif; ?>
