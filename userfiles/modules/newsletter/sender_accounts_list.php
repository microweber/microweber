<?php only_admin_access(); ?>
<?php
$senders = newsletter_get_senders();
?>
<?php if ($senders): ?>
    <div class="table-responsive">
    <table width="100%" border="0" class="mw-ui-table" style="table-layout:fixed">
        <thead>
        <tr>
            <th><?php _e('Name'); ?></th>
            <th><?php _e('From'); ?></th>
            <th><?php _e('Email'); ?></th>
            <th><?php _e('Reply'); ?></th>
            <th><?php _e('Created at'); ?></th>
            <th><?php _e('Active'); ?></th>
            <th width="200px">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($senders as $sender): ?>
            <tr>
                <td>
                    <?php print $sender['name']; ?>
                </td>
                <td>
                    <?php print $sender['from_name']; ?>
                </td>
                <td>
                    <?php print $sender['from_email']; ?>
                </td>
                <td>
                    <?php print $sender['reply_email']; ?>
                </td>
                <td>
                    <?php print $sender['created_at']; ?>
                </td>
                <td>
                    <?php if ($sender['is_active']): ?><?php _e('Yes'); ?><?php else: ?><?php _e('No'); ?><?php endif; ?>
                </td>
                <td>
                    <button class="mw-ui-btn" onclick="edit_sender('<?php print $sender['id']; ?>')"><?php _e('Edit'); ?></button>
                    <a class="mw-ui-btn mw-ui-btn-icon" href="javascript:;" onclick="delete_sender('<?php print $sender['id']; ?>')"> <span class="mw-icon-bin"></span> </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div><?php else: ?>
    <b>No Senders found.</b>
<?php endif; ?>