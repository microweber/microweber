<?php only_admin_access(); ?>
<?php $couponLogs = coupon_logs(); ?>
<?php
if (empty($couponLogs)):
?>

<?php _e('No logs found'); ?>

<?php
else:
?>

<div class="table-responsive">
    <table class="table card-table table-vcenter">
        <thead>
        <tr>
            <th><?php _e('Coupon ID'); ?></th>
            <th><?php _e('Code'); ?></th>
            <th><?php _e('Customer Email'); ?></th>
            <th><?php _e('Customer Ip'); ?></th>
            <th><?php _e('Use Date'); ?></th>
        </tr>
        </thead>
        <?php
        if ($couponLogs): ?>
            <?php foreach ($couponLogs as $couponLog): ?>
                <tr>
                    <td><?php print($couponLog['coupon_id']) ?></td>
                    <td><?php print($couponLog['coupon_code']) ?></td>
                    <td><?php print($couponLog['customer_email']) ?></td>
                    <td><?php print($couponLog['customer_ip']) ?></td>
                    <td><?php print($couponLog['use_date']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</div>
<?php
endif;
?>
