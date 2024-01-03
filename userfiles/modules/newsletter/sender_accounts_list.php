<?php must_have_access(); ?>
<?php
$senders = newsletter_get_senders();
?>


    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4>List of senders</h4>
        </div>
        <a href="javascript:;" class="btn btn-outline-primary mb-3" onclick="edit_sender(false);">
            <i class="mdi mdi-plus"></i> <?php _e('Add new sender'); ?>
        </a>
    </div>

    <?php if ($senders): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="font-weight-bold"><?php _e('Name'); ?></th>
                        <th class="font-weight-bold"><?php _e('From'); ?></th>
                        <th class="font-weight-bold"><?php _e('Email'); ?></th>
                        <th class="font-weight-bold"><?php _e('Reply'); ?></th>
<!--                        <th class="font-weight-bold"><?php _e('Created at'); ?></th>-->
    <!--                    <th class="font-weight-bold"><?php _e('Active'); ?></th>-->
                        <th class="font-weight-bold text-center" width="200px"><?php _e('Action'); ?></th>
                    </tr>
                </thead>

                <tbody class="small">
                    <?php foreach ($senders as $sender): ?>
                        <tr>
                            <td><?php print $sender['name']; ?></td>
                            <td><?php print $sender['from_name']; ?></td>
                            <td><?php print $sender['from_email']; ?></td>
                            <td><?php print $sender['reply_email']; ?></td>
<!--                            <td><?php print $sender['created_at']; ?></td>-->
    <!--                        <td><?php if ($sender['is_active']): ?><?php _e('Yes'); ?><?php else: ?><?php _e('No'); ?><?php endif; ?></td>-->
                            <td class="text-center">
                                <button class="btn btn-outline-primary btn-sm" onclick="edit_sender('<?php print $sender['id']; ?>')"><?php _e('Edit'); ?></button>
                                <button class="btn btn-outline-danger btn-sm" href="javascript:;" onclick="delete_sender('<?php print $sender['id']; ?>')"><i class="mdi mdi-trash-can-outline"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info"><?php _e("No senders found"); ?></div>
    <?php endif; ?>
