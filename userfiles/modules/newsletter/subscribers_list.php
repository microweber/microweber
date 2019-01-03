<?php only_admin_access(); ?>
<?php
$subscribers_params = array();
$subscribers_params['no_limit'] = true;
$subscribers_params['order_by'] = "created_at desc";
$subscribers = newsletter_get_subscribers($subscribers_params);
?>
<?php if ($subscribers): ?>
    <div class="table-responsive">
        <table width="100%" border="0" class="mw-ui-table" style="table-layout:fixed">
            <thead>
            <tr>
                <th><?php _e('Name'); ?></th>
                <th><?php _e('Email'); ?></th>
                <th><?php _e('Date'); ?></th>
                <th><?php _e('Subscribed'); ?></th>
                <th width="140px">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($subscribers as $subscriber): ?>
                <tr>
                    <td>
                    <?php print $subscriber['name']; ?>
                    </td>
                    <td>
                    <?php print $subscriber['email']; ?>
                    </td>
                    <td><?php print $subscriber['created_at']; ?></td>
                    <td>
                    <?php
                    if ($subscriber['is_subscribed']) {
                    		_e('Yes');
                    } else {
                    		_e('No');
                    }
                    ?>
                    </td>
                    <td>
                        <button class="mw-ui-btn" onclick="edit_subscriber('<?php print $subscriber['id']; ?>')"><?php _e('Edit'); ?></button>
                        <a class="mw-ui-btn mw-ui-btn-icon" href="javascript:;" onclick="delete_subscriber('<?php print $subscriber['id']; ?>')"> <span class="mw-icon-bin"></span> </a>
                   </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>