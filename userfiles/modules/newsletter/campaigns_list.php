<?php must_have_access(); ?>
<?php
$campaigns = newsletter_get_campaigns();
?>
<?php if ($campaigns): ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th class="font-weight-bold"><?php _e('Name'); ?></th>
                    <th class="font-weight-bold"><?php _e('Subject'); ?></th>
                    <th class="font-weight-bold"><?php _e('From'); ?></th>
                    <!--<th class="font-weight-bold"><?php _e('Email'); ?></th>  -->
                    <th class="font-weight-bold"><?php _e('Created at'); ?></th>
                    <th class="font-weight-bold"><?php _e('List'); ?></th>
                    <th class="font-weight-bold"><?php _e('Done'); ?></th>
                    <th class="font-weight-bold text-center" width="200px">&nbsp;</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="font-weight-bold"><?php _e('Name'); ?></th>
                    <th class="font-weight-bold"><?php _e('Subject'); ?></th>
                    <th class="font-weight-bold"><?php _e('From'); ?></th>
                    <!--<th class="font-weight-bold"><?php _e('Email'); ?></th>  -->
                    <th class="font-weight-bold"><?php _e('Created at'); ?></th>
                    <th class="font-weight-bold"><?php _e('List'); ?></th>
                    <th class="font-weight-bold"><?php _e('Done'); ?></th>
                    <th class="font-weight-bold text-center" width="200px">&nbsp;</th>
                </tr>
            </tfoot>
            <tbody class="small">
                <?php foreach ($campaigns as $campaign): ?>
                    <tr>
                        <td><?php print $campaign['name']; ?></td>
                        <td><?php print $campaign['subject']; ?></td>
                        <td><?php print $campaign['from_name']; ?></td>
                        <!--
                            <td><?php // print $campaign['from_email'];      ?></td>-->
                        <td><?php print $campaign['created_at']; ?></td>
                        <td><?php print $campaign['list_name']; ?></td>
                        <td>
                            <?php if ($campaign['is_done']): ?>
                                <?php _e('Yes'); ?>
                            <?php else: ?>
                                <?php _e('No'); ?>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-outline-primary btn-sm" onclick="edit_campaign('<?php print $campaign['id']; ?>')"><?php _e('Edit'); ?></button>
                            <a class="btn btn-link btn-sm text-danger" href="javascript:;" onclick="delete_campaign('<?php print $campaign['id']; ?>')"><i class="mdi mdi-trash-can-outline"></i></a>
                            <button class="btn btn-success btn-sm" onclick="start_campaign('<?php print $campaign['id']; ?>')"><?php _e('Start'); ?></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        <?php _e("No campaigns found"); ?>
    </div>
<?php endif; ?>
