<?php must_have_access(); ?>
<?php
$senders = newsletter_get_senders();
?>
<?php if ($senders): ?>
    <div class="form-group">
        <label class="control-label">List of senders</label>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="font-weight-bold"><?php _e('Name'); ?></th>
                    <th class="font-weight-bold"><?php _e('From'); ?></th>
                    <!--<th class="font-weight-bold"><?php _e('Email'); ?></th>-->
                    <!--<th class="font-weight-bold"><?php _e('Reply'); ?></th>-->
                    <th class="font-weight-bold"><?php _e('Created at'); ?></th>
                    <th class="font-weight-bold"><?php _e('Active'); ?></th>
                    <th class="font-weight-bold text-center" width="200px"><?php _e('Action'); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="font-weight-bold"><?php _e('Name'); ?></th>
                    <th class="font-weight-bold"><?php _e('From'); ?></th>
                    <!--<th class="font-weight-bold"><?php _e('Email'); ?></th>-->
                    <!--<th class="font-weight-bold"><?php _e('Reply'); ?></th>-->
                    <th class="font-weight-bold"><?php _e('Created at'); ?></th>
                    <th class="font-weight-bold"><?php _e('Active'); ?></th>
                    <th class="font-weight-bold text-center" width="200px"><?php _e('Action'); ?></th>
                </tr>
            </tfoot>
            <tbody class="small">
                <?php foreach ($senders as $sender): ?>
                    <tr>
                        <td><?php print $sender['name']; ?></td>
                        <td><?php print $sender['from_name']; ?></td>
                        <!--<td><?php print $sender['from_email']; ?></td>-->
                        <!--<td><?php print $sender['reply_email']; ?></td>-->
                        <td><?php print $sender['created_at']; ?></td>
                        <td><?php if ($sender['is_active']): ?><?php _e('Yes'); ?><?php else: ?><?php _e('No'); ?><?php endif; ?></td>
                        <td class="text-center">
                            <button class="btn btn-outline-primary btn-sm" onclick="edit_sender('<?php print $sender['id']; ?>')"><?php _e('Edit'); ?></button>
                            <a class="btn btn-link btn-sm text-danger" href="javascript:;" onclick="delete_sender('<?php print $sender['id']; ?>')"><i class="mdi mdi-trash-can-outline"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="icon-title justify-content-center">
        <i class="mdi mdi-book-account-outline"></i> <h5 class="mb-0"><?php _e("No senders found"); ?></h5>
    </div>
<?php endif; ?>