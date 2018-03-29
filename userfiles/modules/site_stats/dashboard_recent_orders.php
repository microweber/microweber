<?php
only_admin_access();


?>

<?php $orders = get_orders(); ?>

<div class="dashboard-recent">
    <div class="dr-head">
        <span class="drh-activity-name"><i class="mai-shop"></i> <?php _e("Recent Orders") ?></span>
        <a href="#" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline"><?php print _e('Add new order'); ?></a>
        <a href="<?php print admin_url('view:shop/action:orders'); ?>" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info"><strong>2</strong> <?php print _e('New orders'); ?></a>
    </div>
    <div class="dr-list">
        <div class="orders-holder" id="shop-orders">
            <?php include(modules_path() . 'shop/orders/views/orders-list.php'); ?>
        </div>
    </div>
</div>
