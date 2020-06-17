<?php if ($orders): ?>
    <p class="bold p-b-10 p-t-10 m-t-20"><?php print _e('List of all orders'); ?></p>

    <?php foreach ($orders as $item) : ?>
        <?php $is_order = true; ?>
        <?php include module_dir('admin/notifications') . 'notif_order.php'; ?>
    <?php endforeach; ?>
<?php else: ?>
    <div class="icon-title">
        <i class="mdi mdi-shopping"></i> <h5><?php _e('You don\'t have any orders yet.'); ?></h5>
    </div>
<?php endif; ?>
