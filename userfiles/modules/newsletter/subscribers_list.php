<?php must_have_access(); ?>
<?php
$subscribers_params = array();
$subscribers_params['no_limit'] = true;
$subscribers_params['order_by'] = "created_at desc";
$subscribers = newsletter_get_subscribers($subscribers_params);
?>
<?php if ($subscribers): ?>

    <div class="d-flex justify-content-between">
        <div class="form-group">
            <label class="control-label">List of subscribers</label>
        </div>

        <div>
            <?php
            $subscribers_params = array();
            $subscribers_params['no_limit'] = true;
            $subscribers_params['order_by'] = "created_at desc";
            $subscribers = newsletter_get_subscribers($subscribers_params);
            ?>
            <?php if (is_array($subscribers)) : ?>
                <strong><?php print _e('Total'); ?>:</strong>
                <span><?php echo count($subscribers); ?> subscribers</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th class="font-weight-bold" scope="col" width="40px"><?php _e('ID'); ?></th>
                    <th class="font-weight-bold" scope="col"><?php _e('Name'); ?></th>
                    <th class="font-weight-bold" scope="col"><?php _e('E-mail'); ?></th>
                    <th class="font-weight-bold" scope="col"><?php _e('Subscribed at'); ?></th>
                    <th class="font-weight-bold" scope="col"><?php _e('Subscribed'); ?></th>
                    <th class="font-weight-bold text-center" scope="col" width="200px"><?php _e('Action'); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="font-weight-bold" scope="col" width="40px"><?php _e('ID'); ?></th>
                    <th class="font-weight-bold" scope="col"><?php _e('Name'); ?></th>
                    <th class="font-weight-bold" scope="col"><?php _e('E-mail'); ?></th>
                    <th class="font-weight-bold" scope="col"><?php _e('Subscribed at'); ?></th>
                    <th class="font-weight-bold" scope="col"><?php _e('Subscribed'); ?></th>
                    <th class="font-weight-bold text-center" scope="col" width="200px"><?php _e('Action'); ?></th>
                </tr>
            </tfoot>
            <tbody class="small">
                <?php foreach ($subscribers as $key => $subscriber): ?>
                    <tr>
                        <td data-label="<?php _e('#'); ?>"><?php print $key + 1; ?></td>
                        <td data-label="<?php _e('Name'); ?>"><?php print $subscriber['name']; ?></td>
                        <td data-label="<?php _e('E-mail'); ?>"><?php print $subscriber['email']; ?></td>
                        <td data-label="<?php _e('Subscribed at'); ?>"><?php print $subscriber['created_at']; ?></td>
                        <td data-label="<?php _e('Subscribed'); ?>">
                            <?php
                            if ($subscriber['is_subscribed']) {
                                _e('Yes');
                            } else {
                                _e('No');
                            }
                            ?>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-outline-primary btn-sm" onclick="edit_subscriber('<?php print $subscriber['id']; ?>')"><?php _e('Edit'); ?></button>
                            <a class="btn btn-link btn-sm text-danger" href="javascript:;" onclick="delete_subscriber('<?php print $subscriber['id']; ?>')"><i class="mdi mdi-trash-can-outline"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info"><?php _e("You don't have any subscribers yet"); ?></div>
<?php endif; ?>
