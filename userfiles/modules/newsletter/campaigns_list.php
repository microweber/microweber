<?php must_have_access(); ?>


<div class="mb-4">
    <div class="alert alert-info">
        Add this to your cron jobs to process campaigns. Frequency must be every 30 minutes.
        <div>
            <code class="style:font-weight:bold;">
                php artisan panel:process-campaigns
            </code>
        </div>
    </div>
</div>

<?php
$campaigns = newsletter_get_campaigns();
?>
<?php if ($campaigns): ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th class="font-weight-bold"><?php _e('Name'); ?></th>
<!--                    <th class="font-weight-bold"><?php _e('Subject'); ?></th>
                    <th class="font-weight-bold"><?php _e('From'); ?></th>-->
                    <!--<th class="font-weight-bold"><?php _e('Email'); ?></th>  -->
<!--                    <th class="font-weight-bold"><?php _e('Created at'); ?></th>-->
                    <th class="font-weight-bold"><?php _e('List'); ?></th>
                    <th class="font-weight-bold"><?php _e('Scheduled'); ?></th>
                    <th class="font-weight-bold"><?php _e('Scheduled At'); ?></th>
                    <th class="font-weight-bold"><?php _e('Done'); ?></th>
                    <th class="font-weight-bold text-center" width="200px">&nbsp;</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="font-weight-bold"><?php _e('Name'); ?></th>
<!--                    <th class="font-weight-bold"><?php _e('Subject'); ?></th>
                    <th class="font-weight-bold"><?php _e('From'); ?></th>-->
                    <!--<th class="font-weight-bold"><?php _e('Email'); ?></th>  -->
<!--                    <th class="font-weight-bold"><?php _e('Created at'); ?></th>-->
                    <th class="font-weight-bold"><?php _e('List'); ?></th>
                    <th class="font-weight-bold"><?php _e('Scheduled'); ?></th>
                    <th class="font-weight-bold"><?php _e('Scheduled At'); ?></th>
                    <th class="font-weight-bold"><?php _e('Done'); ?></th>
                    <th class="font-weight-bold text-center" width="200px">&nbsp;</th>
                </tr>
            </tfoot>
            <tbody class="small">
                <?php foreach ($campaigns as $campaign): ?>
                    <tr>
                        <td><?php print $campaign['name']; ?></td>
<!--                        <td><?php print $campaign['subject']; ?></td>
                        <td><?php print $campaign['from_name']; ?></td>-->
                        <!--
                            <td><?php // print $campaign['from_email'];      ?></td>-->
<!--                        <td><?php print $campaign['created_at']; ?></td>-->
                        <td><?php print $campaign['list_name']; ?></td>
                        <td>
                            <?php if ($campaign['is_scheduled']): ?>
                                <?php _e('Yes'); ?>
                            <?php else: ?>
                                <?php _e('No'); ?>
                            <?php endif; ?>
                        </td>
                        <td><?php
                            if (!empty($campaign['scheduled_at'])) {
                                echo $campaign['scheduled_at'];
                            } else {
                                echo 'N/A';
                            }
                            ?></td>
                        <td>
                            <?php if ($campaign['is_done']): ?>
                                <?php _e('Yes'); ?>
                            <?php else: ?>
                                <?php _e('No'); ?>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-outline-primary btn-sm" onclick="edit_campaign('<?php print $campaign['id']; ?>')"><?php _e('Edit'); ?></button>
                            <a class="btn btn-outline-danger btn-sm" href="javascript:;" onclick="delete_campaign('<?php print $campaign['id']; ?>')"><i class="mdi mdi-trash-can-outline"></i></a>
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
