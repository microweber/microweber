<div class="messages-holder">
    <?php
    if ($last_messages): ?>
        <?php foreach ($last_messages as $item) : ?>
            <?php $is_entry = true; ?>
            <?php include module_dir('admin/notifications') . 'notif_form_entry.php'; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="icon-title d-flex">
            <h5><?php _e('You don\'t have any messages yet.'); ?></h5>
        </div>
    <?php endif; ?>
</div>
